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
        return EventResource::collection(
            Event::where(function($query) {
                $query->where('id', 'like', request('search') . '%')
                ->orWhere('singer_name', 'like', '%' . request('search') . '%');
            })
            ->orderBy('date', 'desc')
            ->paginate(10)
        );
    }

    public function visible_events()
    {
        return EventResource::collection(Event::orderBy('date')->where('is_visible', true)->get());
    }

    public function allEvents()
    {
        return EventResource::collection(
            // Event::where('date', '>', Carbon::now())->orderBy('date', 'desc')->get()
            Event::orderBy('date', 'desc')->get()
        );
    }

    public function visible_event($id)
    {
        return new EventResource(Event::where('is_visible', true)->where('id', $id)->firstOrFail());
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

    public function hide_tables($id, Request $request)
    {
        $event = Event::findOrFail($id);

        if ($this->isOverlaps($event->id, $request->ids)) {
            return response()->json([
                'message' => 'overlapping'
            ], 500);
        }
        
        $event->unavailableTables()->attach($request->ids);

        $event->unavailableTables()->detach($request->removed_ids);
    }
}
