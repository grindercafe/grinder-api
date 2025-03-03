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
        'description',
        'singer_name',
        'singer_img',
        'price',
        'is_visible'
    ];

    protected $casts = [
        'date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function unavailableTables()
    {
        return $this->belongsToMany(Table::class, 'unavailable_tables', 'event_id', 'table_id')->withTimestamps();
    }
}
