<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // booking, one-to-many
    // booking can have one status, and status can belongs to many booking

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
