<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
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
        $booking = [
            'party_size'=> $request->event_id,
            'total_price'=> $request->total_price,
            "event_id"=> $request->event_id,
            'customer_id'=> $request->customer_id,
            'booking_status_id'=> $request->booking_status_id,
            'booking_number'=> $request->booking_number
        ];

        $createdBooking = Booking::create($booking);
        
        return response()->json([
            'success'=> true,
            'message'=> 'booking created successfully',
            'data'=> $createdBooking
        ]);
    }

    public function delete($id)
    {
        $booking = Booking::findOrFail($id);

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
