<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Table;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'customer_id',
        'total_price',
        'cancelled_at',
        'is_message_sent',
    ];

    public static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
            $model->token = Str::random(32);
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

    public function tables()
    {
        return $this->belongsToMany(Table::class);
    }

}
