<?php

namespace App\Http\Resources;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Payment;
use App\Models\Table;
use Carbon\Carbon;
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
            'event'=> Event::find($this->event_id),
            'customer'=> Customer::findOrFail($this->customer_id),
            'total_price'=> $this->total_price,
            'is_message_sent'=> $this->is_message_sent,
            'cancelled_at'=> $this->cancelled_at,
            'token'=> $this->token,
            'uuid'=> $this->uuid,
            'tables'=> Booking::findOrFail($this->id)->tables,
            'payment'=> Payment::where('booking_id', $this->id)->first(),
            'created_at'=> $this->created_at
        ];
    }
}
