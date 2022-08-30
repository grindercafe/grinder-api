<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
            'date'=> $this->date,
            'start_time'=> $this->start_time,
            'end_time'=> $this->end_time,
            'singer_name'=> $this->singer_name,
            'singer_img'=> $this->singer_img,
            'price'=> $this->price,
            'description'=> $this->description,
            'bookings'=> Booking::with('tables')->where('event_id', $this->id)->get(),
            'unavailableTables'=> $this->unavailableTables,
            'is_visible'=> $this->is_visible
        ];
    }
}
