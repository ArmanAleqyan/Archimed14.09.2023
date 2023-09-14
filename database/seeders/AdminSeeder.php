<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'name' => 'admin',
                'surname' => 'admin',
                'middle_name' => 'admin',
                'gender' => 'Male',
                'date_of_birth' => Carbon::now(),
                'email' => 'admin@mail.ru',
                'city_name' => 'Москва',
                'city_id' => '1',
                'phone' => '+788888888',
                'phone_code' => '1',
                'geo_dostup' => '1',
                'role_id' => '1',
                'password' => Hash::make('11111111'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
