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
        'booking_status_id'
    ];
}
