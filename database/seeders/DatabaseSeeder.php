<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Customer;
use App\Models\Event;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $price_per_person = 150.0;
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Event::create([
            'date'=> '2022-07-20',
            'start_time'=> '09:00',
            'end_time'=> '00:00',
            'singer_name'=> 'name',
            'singer_img'=> 'img',
            'available_chairs'=> 140,
            'price_per_person'=> $price_per_person,
        ]);

        Event::create([
            'date'=> '2022-07-21',
            'start_time'=> '09:00',
            'end_time'=> '00:00',
            'singer_name'=> 'name1',
            'singer_img'=> 'img1',
            'available_chairs'=> 140,
            'price_per_person'=> $price_per_person,
        ]);

        Event::create([
            'date'=> '2022-07-22',
            'start_time'=> '09:00',
            'end_time'=> '00:00',
            'singer_name'=> 'name2',
            'singer_img'=> 'img2',
            'available_chairs'=> 140,
            'price_per_person'=> $price_per_person,
        ]);

        Customer::create([
            'name'=> 'mubarak',
            'phone_number'=> '0500000000'
        ]);

        Customer::create([
            'name'=> 'ahmed',
            'phone_number'=> '0500000001'
        ]);

        Customer::create([
            'name'=> 'sohad',
            'phone_number'=> '0500000002'
        ]);

        Booking::factory(20)->create();

    }
}
