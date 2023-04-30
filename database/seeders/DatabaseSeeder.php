<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Run the database seeder.
     */

    public function run(): void
    {
        $this->call([
            MBankSeeder::class,
            MGenderSeeder::class,
            MProvinceSeeder::class,
            MCitySeeder::class,
            MDistrictSeeder::class,
            MVillageSeeder::class,
            PostalZipSeeder::class
        ]);
    }
}
