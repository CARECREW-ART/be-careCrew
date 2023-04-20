<?php

namespace Database\Seeders;

use App\Models\Master\MCity;
use App\Models\Master\Mdistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MdistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataDistricts = file_get_contents('./jsonSeeder/districts.json');
        $dataJsondistricts = json_decode($dataDistricts, true);

        $dataCities = file_get_contents('./jsonSeeder/cities.json');
        $dataJsonCities = json_decode($dataCities, true);

        foreach ($dataJsondistricts as $district) {
            foreach ($dataJsonCities as $Cities) {
                if ($district["regency_id"] == $Cities["id"]) {
                    Mdistrict::create([
                        'city_id' => $Cities['id'],
                        'district_id' => $district['id'],
                        'district_name' => $district['name']
                    ]);
                }
            }
        }
    }
}
