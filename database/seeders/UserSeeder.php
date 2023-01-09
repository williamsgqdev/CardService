<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                "name" => "Williams Agbunu",
                "email" => "williams@tingo.ng",
                "password" => Hash::make("12345678"),
                "api_key" => "776dfab7-85bd-403d-8fd5-2f6960a3b19d"
            ]
        ];

        foreach ($data as $d) {
            DB::table('users')->insert($d);
        }
    }
}
