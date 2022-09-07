<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'date'=> fake()->dateTime(),
            'start_time'=> '21:00',
            'end_time'=> '00:00',
            'description'=> fake()->text(),
            'singer_name'=> fake()->name(),
            'singer_img'=> fake()->imageUrl(),
            'price'=> 200.0,
            'is_visible'=> fake()->numberBetween(0, 1)
        ];
    }
}
