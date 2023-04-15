<?php

namespace Database\Seeders;

use App\Models\Master\MCity;
use App\Models\Master\MProvince;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class MCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = file_get_contents('./jsonSeeder/cities.json');
        $dataJsonCity = json_decode($data, true);
        
        $dataProvinces = MProvince::all();
        //get MProvince database
        
        foreach ($dataJsonCity as $city){
           foreach ($dataProvinces as $Provinces){
            if ($city["province_id"]==$Provinces["province_id"]){
                MCity::create([
                    'province_id'=>$Provinces['province_id'],
                    'city_id' => $city['id'],
                    'city_name' => $city['name']
                ]);
            }
           }    
        }
        //
    }
}
