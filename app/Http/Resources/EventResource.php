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
            'available_chairs'=> $this->available_chairs,
            'price_per_person'=> $this->price_per_person
        ];
    }
}
