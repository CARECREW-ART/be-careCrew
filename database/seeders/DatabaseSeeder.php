<?php

namespace Database\Seeders;

use App\Models\Master\MBank;
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
            MBank::class,
            MProvinceSeeder::class,
            MCitySeeder::class,
            MDistrictSeeder::class,
            MVillageSeeder::class
        ]);
    }
}
