<?php

namespace Database\Seeders;

use App\Models\Master\MPostalzip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostalZipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPostal = file_get_contents('./jsonSeeder/postal_array.json');
        $dataJsonPostalZip = json_decode($dataPostal, true);

        $dataDataVillage = file_get_contents('./jsonSeeder/villages.json');
        $dataJsonVillage = json_decode($dataDataVillage, true);


        foreach ($dataJsonPostalZip as $postal) {
            foreach ($dataJsonVillage as $village) {
                if (substr($village['id'], 0, 2) == $postal['province_code']) {
                    if (strcmp($postal['urban'], $village['name']) == 0) {
                        MPostalzip::create([
                            "village_id" => $village['id'],
                            "postalzip_value" => $postal['postal_code']
                        ]);
                    }
                }
            }
        }
    }
}
