<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTable extends Model
{
    use HasFactory;

    protected $table = 'booking_table';

    protected $fillable = [
        'booking_id',
        'table_id'
    ];
}
