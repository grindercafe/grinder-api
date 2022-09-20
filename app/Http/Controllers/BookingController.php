<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\BookingResource;
use App\Models\Event;
use App\Models\Table;

class BookingController extends Controller
{
    public function index()
    {
        return BookingResource::collection(
            Booking::where(function ($query) {
                $query->where('id', 'LIKE', request('search') . '%')
                    ->orWhereHas('customer', function ($query) {
                        $query->where('phone_number', 'LIKE', '%' . request('search') . '%');
                    })->orWhereHas('event', function ($query) {
                        $query->where('singer_name', 'LIKE', '%' . request('search') . '%');
                    });
            })->latest()->paginate(10)
        );
        // $ids = Event::where('date', '>=', now()->toDateString())->pluck('id');
        // return BookingResource::collection(
        //     // Booking::whereIn('event_id', $ids)
        //     Booking::where('id', 'LIKE', '%' . request('search') . '%')
        //         ->orWhereHas('customer', function ($q) {
        //             $q->where('name', 'LIKE', '%' . request('search') . '%');
        //         })
        //         // })
        //         // ->where('id', 'LIKE',)
        //         // ->whereHas('payment', function($q) {
        //         //     $q->where('status', '<>', 'timeout');
        //         // })
        //         ->paginate(3)
        // );
        // return BookingResource::collection(Booking::latest()->whereHas('payment', function($q) {
        //     $q->where('status', '<>', 'timeout');
        // })->get());
    }

    public function show($uuid, Request $request)
    {
        return new BookingResource(Booking::where('uuid', $uuid)->where('token', $request->query('token'))->firstOrFail());
    }

    public function showInDashboard($id)
    {
        return new BookingResource(Booking::findOrFail($id));
    }

    public function test()
    {
        return Booking::with('tables')->where('event_id', 1)->get();
    }

    public function isOverlaps($event_id, $tables)
    {
        $bookings = Booking::where('event_id', $event_id)->get();

        $overlap = false;

        foreach ($bookings as $booking) {
            foreach ($booking->tables as $table) {
                foreach ($tables as $selectedTable) {
                    if ($selectedTable == $table->id) {
                        $overlap = true;
                        break 3;
                    }
                }
            }
        }
        return $overlap;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'customer' => 'required|array',
            'customer.name' => 'required|max:255',
            'customer.phone_number' => ['required', 'regex:/^(05)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/']
        ]);

        if ($this->isOverlaps($request->event_id, $request->tables)) {
            return response()->json([
                'message' => 'overlapping'
            ], 500);
        }

        $customer = Customer::where('phone_number', $request->customer['phone_number'])->first();

        if (!$customer) {
            $customer = Customer::create([
                'name' => $request->customer['name'],
                'phone_number' => $request->customer['phone_number']
            ]);
        }

        $booking = [
            'total_price' => $request->total_price,
            "event_id" => $request->event_id,
            'customer_id' => $customer->id
        ];

        $createdBooking = Booking::create($booking);

        Payment::create([
            'transactionNo' => $request->transactionNo,
            'booking_id' => $createdBooking->id
        ]);


        $createdBooking->tables()->attach($request->tables);


        return response()->json([
            'success' => true,
            'message' => 'booking created successfully',
            'data' => $createdBooking
        ]);
    }

    public function overlappedTables($targetEvent, $currentTables)
    {
        $overlappedTables = [];

        foreach ($targetEvent->bookings as $singleBooking) {
            foreach ($singleBooking->tables as $singleTable) {
                foreach ($currentTables as $table) {
                    if ($table->id == $singleTable->id) {
                        array_push($overlappedTables, $table->number);
                    }
                }
            }
        }

        return $overlappedTables;
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        if ($request->paymentStatus)
            $booking->payment->update(['status' => $request->paymentStatus]);

        return response()->json([
            'success' => true,
            'message' => 'booking updated successfully'
        ]);
    }

    public function updateRelatedEvent(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $currentTables = $booking->tables;
        $targetEvent = Event::findOrFail($request->event);

        if ($request->event) {
            $targetEvent = Event::findOrFail($request->event);

            if ($this->overlappedTables($targetEvent, $currentTables)) {
                return response()->json([
                    'message' => 'overlapping',
                    'tables' => $this->overlappedTables($targetEvent, $currentTables)
                ], 500);
            }

            $booking->update(['event_id' => $request->event]);
        }

        return response()->json([
            'success' => true,
            'message' => 'booking updated successfully'
        ]);
    }

    public function update_tables(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $selectedTables = Table::whereIn('id', $request->ids)->get();
        $selectedTablesIds = Table::whereIn('id', $request->ids)->pluck('id');
        $removedTables = Table::whereIn('id', $request->removed_ids)->get();

        if ($this->isOverlaps($booking->event->id, $selectedTablesIds)) {
            return response()->json([
                'message' => 'overlapping'
            ], 500);
        }

        $event = $booking->event;

        $total_price = $booking->total_price;

        foreach ($selectedTables as $table) {
            $total_price = $total_price + ($event->price * $table->capacity);
        }

        foreach ($removedTables as $table) {
            $total_price = $total_price - ($event->price * $table->capacity);
        }

        $booking->update(['total_price' => $total_price]);
        $booking->tables()->detach($request->removed_ids);
        $booking->tables()->attach($request->ids);


        return response()->json([
            'success' => true,
            'message' => 'booking tables updated successfully',
            'data' => $booking
        ]);
    }

    public function delete($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->tables()->detach();

        if ($booking->payment)
            $booking->payment->delete();

        $booking->destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'booking deleted successfully',
            'data' => $booking
        ]);
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update(['cancelled_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'booking cancelled successfully',
            'data' => $booking
        ]);
    }

    public function messageStatus($id)
    {
        $booking = Booking::findOrFail($id);

        $is_message_sent = $booking->is_message_sent;

        $booking->update(['is_message_sent' => !$is_message_sent]);

        return response()->json([
            'success' => true,
            'message' => 'message status updated successfully',
            'data' => $booking
        ]);
    }

    public function bookings_by_event($event_id)
    {
        return Booking::where('event_id', $event_id)->get();
    }
}
