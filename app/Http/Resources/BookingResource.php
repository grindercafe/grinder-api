<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'singer_name'=> $this->singer_name,
            'bookings'=> Booking::where('event_id', $this->id)->count(),
        ];
    }
}
