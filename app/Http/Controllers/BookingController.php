<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return BookingResource::collection(Booking::latest()->get());
    }

    public function show($uuid, Request $request)
    {
        return new BookingResource(Booking::where('uuid', $uuid)->where('token', $request->query('token'))->firstOrFail());
    }

    public function isOverlaps()
    {
        return Booking::with(['tables' => function ($q) {
            $q->where('capacity', 4);
        }])
            ->where('event_id', 1)
            ->get();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'event_id' => 'required',
            'customer' => 'required|array',
            'customer.name' => 'required|max:255',
            'customer.phone_number' => ['required', 'regex:/^(05)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/']
        ]);

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

    public function delete($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->tables()->detach();

        Payment::where('booking_id', $booking->id)->first()->delete();

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

    public function activate($id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update(['cancelled_at' => null]);
    }
}
