<?php

namespace Database\Seeders;

use App\Models\Master\MVillage;
use App\Models\Master\MDistrict;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MVillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataVillage = file_get_contents('./jsonSeeder/villages.json');
        $dataJsonVillage = json_decode($dataVillage, true);

        $dataDistrict = file_get_contents('./jsonSeeder/districts.json');
        $dataJsonDistrict = json_decode($dataDistrict, true);



        foreach ($dataJsonVillage as $village) {
            foreach ($dataJsonDistrict as $District) {
                if ($village["district_id"] == $District["id"]) {
                    MVillage::create([
                        'district_id' => $District['id'],
                        'village_id' => $village['id'],
                        'village_name' => $village['name']
                    ]);
                }
            }
        }
        //
    }
}
