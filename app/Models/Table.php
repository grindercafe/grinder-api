<?php

namespace App\Models;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'capacity'
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class);
    }
}
