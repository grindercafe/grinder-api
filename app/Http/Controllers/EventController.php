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
        return EventResource::collection(Event::orderBy('date')->get());
    }
    
    public function show($id)
    {
        return new EventResource(Event::where('is_visible', true)->where('id', $id)->firstOrFail());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'date'=> 'required|date',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i',
            'singer_name'=> 'required|string',
            'singer_img'=> 'required|url',
            'price'=> 'required|numeric'
        ]);

        $event = [
            'date'=> $request->date,
            'start_time'=> $request->start_time,
            'end_time'=> $request->end_time,
            'singer_name'=> $request->singer_name,
            'singer_img'=> $request->singer_img,
            'price'=> $request->price,
            'description'=> $request->description
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

    public function visible_events()
    {
        return EventResource::collection(Event::orderBy('date')->where('is_visible', true)->get());
    }

    public function update_is_visible($id)
    {
        $event = Event::find($id);

        $event->update(['is_visible'=> !$event->is_visible]);

        return response()->json([
            'success'=> true,
            'message'=> 'event visibility updated successfully',
            'data'=> $event
        ]);
    }

    public function bookings_by_event($event_id)
    {
        return Booking::where('event_id', $event_id)->count();
    }
}
