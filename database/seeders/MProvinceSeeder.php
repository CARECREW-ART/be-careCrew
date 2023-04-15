<?php

namespace Database\Seeders;

use App\Models\Master\MProvince;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = file_get_contents('./jsonSeeder/provinces.json');
        $dataJsonProvince = json_decode($data, true);
        
        foreach ($dataJsonProvince as $province){
            MProvince::create([
                'province_id' => $province['id'],
                'province_name' => $province['name']
            ]);    
        }
        //
    }
}
