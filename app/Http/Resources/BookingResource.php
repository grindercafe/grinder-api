<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\Customer;
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
            'booking_number'=> $this->booking_number,
            'event'=> Event::findOrFail($this->event_id),
            'customer'=> Customer::findOrFail($this->customer_id),
            'party_size'=> $this->party_size,
            'total_price'=> $this->total_price,
            'is_message_sent'=> $this->is_message_sent,
            'cancelled_at'=> $this->cancelled_at
        ];
    }
}
