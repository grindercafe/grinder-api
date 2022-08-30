<?php

namespace App\Http\Resources;

use App\Models\Booking;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'phone_number' => $this->phone_number,
            'token' => $this->token,
            'uuid' => $this->uuid,
            'num_of_bookings' => Booking::whereHas('payment', function ($q) {
                $q->where('status', 'paid');
            })->where('customer_id', $this->id)->count()
        ];
    }
}
