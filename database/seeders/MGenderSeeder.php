<?php

namespace Database\Seeders;

use App\Models\Master\MGender;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MGenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $objectGender = array(
            "0" => "Not known",
            "1" => "Male",
            "2" => "Female",
            "9" => "Not applicable"
        );

        foreach ($objectGender as $key => $gender) {
            MGender::create([
                "gender_bit" => $key,
                "gender_value" => $gender
            ]);
        }
    }
}
