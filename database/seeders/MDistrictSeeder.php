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
        $data = file_get_contents('./jsonSeeder/districts.json');
        $dataJsondistrict = json_decode($data, true);

        $dataCities = MCity::all();
        //get MCity database

        foreach ($dataJsondistrict as $district){
            foreach ($dataCities as $Cities){
             if ($district["regency_id"]==$Cities["city_id"]){
                 Mdistrict::create([
                     'city_id'=>$Cities['city_id'],
                     'district_id' => $district['id'],
                     'district_name' => $district['name']
                 ]);        
    }
            }
        }
    }
}
    
