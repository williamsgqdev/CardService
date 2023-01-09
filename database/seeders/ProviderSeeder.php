<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data =  [
            [
                "name" => "SUDO",
                "description" => "Sudo Africa for card generation",
                "active" => 1
            ],
        ];

        foreach ($data as $d) {
            DB::table('providers')->insert($d);
        }
    }
}
