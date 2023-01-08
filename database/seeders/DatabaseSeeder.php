<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\Customer;
use App\Models\Event;
use App\Models\Table;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Event::create([
        //     'date'=> '2022-07-20',
        //     'start_time'=> '21:00',
        //     'end_time'=> '00:00',
        //     'description'=> 'وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي وصف نصي',
            
        //     'singer_name'=> 'راشد الفهد',
        //     'singer_img'=> 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80',
        //     'price'=> 150.0,
        // ]);

        // Event::create([
        //     'date'=> '2022-07-21',
        //     'start_time'=> '21:00',
        //     'end_time'=> '00:00',
        //     'description'=> 'وصف نصي, , وصف نصي',
        //     'singer_name'=> 'خالد الماجد',
        //     'singer_img'=> 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80',
        //     'price'=> 200.0,
        // ]);

        // Event::create([
        //     'date'=> '2022-07-22',
        //     'start_time'=> '21:00',
        //     'end_time'=> '00:00',
        //     'description'=> 'وصف نصي, وصفف , وصف نصي',
        //     'singer_name'=> 'رابح عبدالله',
        //     'singer_img'=> 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80',
        //     'price'=> 180.0,
        // ]);

        // Customer::create([
        //     'name'=> 'mubarak',
        //     'phone_number'=> '0500000000'
        // ]);

        // Customer::create([
        //     'name'=> 'ahmed',
        //     'phone_number'=> '0500000001'
        // ]);

        // Customer::create([
        //     'name'=> 'sohad',
        //     'phone_number'=> '0500000002'
        // ]);

        Table::create(['number'=> 34, 'capacity'=> 4]);
        Table::create(['number'=> 33, 'capacity'=> 3]);
        Table::create(['number'=> 32, 'capacity'=> 4]);
        Table::create(['number'=> 31, 'capacity'=> 2]);
        Table::create(['number'=> 30, 'capacity'=> 4]);
        Table::create(['number'=> 29, 'capacity'=> 4]);
        Table::create(['number'=> 35, 'capacity'=> 3]);
        Table::create(['number'=> 37, 'capacity'=> 3]);
        Table::create(['number'=> 28, 'capacity'=> 2]);
        Table::create(['number'=> 26, 'capacity'=> 4]);
        Table::create(['number'=> 25, 'capacity'=> 4]);
        Table::create(['number'=> 36, 'capacity'=> 3]);
        Table::create(['number'=> 38, 'capacity'=> 2]);
        Table::create(['number'=> 27, 'capacity'=> 2]);
        Table::create(['number'=> 39, 'capacity'=> 3]);
        Table::create(['number'=> 40, 'capacity'=> 2]);
        Table::create(['number'=> 24, 'capacity'=> 2]);
        Table::create(['number'=> 23, 'capacity'=> 2]);
        Table::create(['number'=> 22, 'capacity'=> 2]);
        Table::create(['number'=> 21, 'capacity'=> 2]);
        Table::create(['number'=> 20, 'capacity'=> 2]);
        Table::create(['number'=> 19, 'capacity'=> 4]);
        Table::create(['number'=> 41, 'capacity'=> 3]);
        Table::create(['number'=> 42, 'capacity'=> 3]);
        Table::create(['number'=> 16, 'capacity'=> 2]);
        Table::create(['number'=> 17, 'capacity'=> 2]);
        Table::create(['number'=> 18, 'capacity'=> 2]);
        Table::create(['number'=> 15, 'capacity'=> 2]);
        Table::create(['number'=> 43, 'capacity'=> 3]);
        Table::create(['number'=> 44, 'capacity'=> 3]);
        Table::create(['number'=> 10, 'capacity'=> 2]);
        Table::create(['number'=> 11, 'capacity'=> 2]);
        Table::create(['number'=> 12, 'capacity'=> 2]);
        Table::create(['number'=> 13, 'capacity'=> 2]);
        Table::create(['number'=> 14, 'capacity'=> 4]);
        Table::create(['number'=> 45, 'capacity'=> 2]);
        Table::create(['number'=> 7, 'capacity'=> 2]);
        Table::create(['number'=> 8, 'capacity'=> 2]);
        Table::create(['number'=> 9, 'capacity'=> 2]);
        Table::create(['number'=> 4, 'capacity'=> 2]);
        Table::create(['number'=> 5, 'capacity'=> 2]);
        Table::create(['number'=> 6, 'capacity'=> 2]);
        Table::create(['number'=> 1, 'capacity'=> 4]);
        Table::create(['number'=> 2, 'capacity'=> 4]);
        Table::create(['number'=> 3, 'capacity'=> 4]);
        // Customer::factory(300)->create();
        // Event::factory(200)->create();
        // Booking::factory(400)->create();


    }
}
