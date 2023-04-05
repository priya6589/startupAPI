<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['name' => 'Afghanistan'],
            ['name' => 'Armenia'],
            ['name' => 'Azerbaijan'],
            ['name' => 'Bahrain'],
            ['name' => 'Bangladesh'],
            ['name' => 'Bhutan'],
            ['name' => 'Brunei'],
            ['name' => 'Cambodia'],
            ['name' => 'China'],
            ['name' => 'Cyprus'],
            ['name' => 'Georgia'],
            ['name' => 'India'],
            ['name' => 'Indonesia'],
            ['name' => 'Iran'],
            ['name' => 'Iraq'],
            ['name' => 'Israel'],
            ['name' => 'Japan'],
            ['name' => 'Jordan'],
            ['name' => 'Kazakhstan'],
            ['name' => 'Korea'],
            // Add more countries here...
        ];

        DB::table('countries')->insert($countries);
    }
}
