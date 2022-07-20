<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $party_size = fake()->numberBetween(2, 10);
        return [
            'party_size'=> $party_size,
            'total_price'=> $party_size * $price_per_person,
            'event_id'=> fake()->numberBetween(1, 3),
            'customer_id'=> fake()->numberBetween(1, 3),
            'booking_status_id'=> fake()->numberBetween(1, 3)
        ];
    }
}
