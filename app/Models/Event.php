<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'singer_name',
        'singer_img',
        'available_chairs',
        'price_per_person'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
