<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller 
{
    public function index()
    {
        return Booking::all();
        // return BookingResource::collection(Booking::all());
    }

    public function show($id)
    {
        return Booking::findOrFail($id);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'party_size'=> 'required|numeric|max:200',
            'event_id'=> 'required',
            'customer'=> 'required|array',
            'customer.name'=> 'required|max:255',
            'customer.phone_number'=> 'required|unique:App\Models\Customer,phone_number'
        ]);

        $event = Event::findOrFail($request->event_id);

        if($request->party_size <= 0) {
            return response()->json([
                'success'=> false,
                'message'=> 'you can\'t select less than 1'
            ]);
        }

        if($event->is_over_available_seats($request->party_size)) {
            return response()->json([
                'success'=> false,
                'message'=> 'exceeded the number of available seats'
            ]);
        }

        $customer = Customer::create([
            'name'=> $request->customer['name'],
            'phone_number'=> $request->customer['phone_number']
        ]);
        
        $booking = [
            'party_size'=> $request->party_size,
            'total_price'=> $event->price_per_person * $request->party_size,
            "event_id"=> $request->event_id,
            'customer_id'=> $customer->id,
            'booking_status_id'=> $request->booking_status_id,
            'booking_number'=> $request->booking_number
        ];

        $createdBooking = Booking::create($booking);
        
        $event->update([
            'available_chairs'=> 
            $event->decrease_available_seats($request->party_size),
        ]);

        return response()->json([
            'success'=> true,
            'message'=> 'booking created successfully',
            'data'=> $createdBooking
        ]);
    }

    public function delete($id)
    {
        $booking = Booking::findOrFail($id);

        $event = Event::findOrFail($booking->event_id);

        $event->update([
            'available_chairs'=> $event->increase_available_seats($request->party_size)
        ]);

        $booking->destroy($id);

        return response()->json([
            'success'=> true,
            'message'=> 'booking deleted successfully',
            'data'=> $booking
        ]);
    }

    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);

        $event = Event::findOrFail($booking->event_id);

        $event->update([
            'available_chairs'=> $event->increase_available_seats($request->party_size)
        ]);

        $booking->update(['cancelled_at'=> now()]);

        return response()->json([
            'success'=> true,
            'message'=> 'booking cancelled successfully',
            'data'=> $booking
        ]);
    }

    public function messageStatus($id)
    {
        $booking = Booking::findOrFail($id);

        $is_message_sent = $booking->is_message_sent;

        $booking->update(['is_message_sent'=> !$is_message_sent]);

        return response()->json([
            'success'=> true,
            'message'=> 'message status updated successfully',
            'data'=> $booking
        ]);
    }

    public function bookings_by_event($event_id)
    {
        return Booking::where('event_id', $event_id)->get();
    }
}
