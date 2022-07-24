<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'party_size',
        'total_price',
        'event_id',
        'customer_id',
        'booking_status_id',
        'cancelled_at',
        'is_message_sent'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
}
