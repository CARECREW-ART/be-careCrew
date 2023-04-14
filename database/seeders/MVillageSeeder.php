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
        $data = file_get_contents('./jsonSeeder/villages.json');
        $dataJsonVillage = json_decode($data, true);
        
        $dataDistrict = MDistrict::all();
        //get MDistrict database
        
        foreach ($dataJsonVillage as $village){
           foreach ($dataDistrict as $Districts){
            if ($village["district_id"]==$Districts["district_id"]){
                MVillage::create([
                    'district_id'=>$Districts['district_id'],
                    'village_id' => $village['id'],
                    'village_name' => $village['name']
                ]);
            }
           }    
        }
        //
    }
}
