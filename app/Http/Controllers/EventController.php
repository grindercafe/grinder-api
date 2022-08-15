<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return EventResource::collection(Event::all());
    }
    
    public function show($id)
    {
        return new EventResource(Event::findOrFail($id));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date'=> 'required|date',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i',
            'singer_name'=> 'required|string',
            'singer_img'=> 'required|url',
            'available_chairs'=> 'required|numeric',
            'price_per_person'=> 'required'
        ]);

        $event = [
            'date'=> $request->date,
            'start_time'=> $request->start_time,
            'end_time'=> $request->end_time,
            'singer_name'=> $request->singer_name,
            'singer_img'=> $request->singer_img,
            'available_chairs'=> $request->available_chairs,
            'price_per_person'=> $request->price_per_person
        ];
        
        $createdEvent = Event::create($event);
        
        return response()->json([
            'success'=> true,
            'message'=> 'event created successfully',
            'data'=> $createdEvent
        ]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $event->update($request->all());

        return response()->json([
            'success'=> true,
            'message'=> 'event updated successfully',
            'data'=> $event
        ]);
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        $event->destroy($id);

        return response()->json([
            'success'=> true,
            'message'=> 'event deleted successfully',
            'data'=> $event
        ]);
    }

    public function bookings_by_event($event_id)
    {
        return Booking::where('event_id', $event_id)->count();
    }
}
