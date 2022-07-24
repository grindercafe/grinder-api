<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_size',
        'total_price',
        'event_id',
        'customer_id',
        'cancelled_at',
        'is_message_sent'
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->booking_number = mt_rand(111111, 999999);
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
