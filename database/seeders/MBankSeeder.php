<?php

namespace Database\Seeders;

use App\Models\Master\MBank;
use Illuminate\Database\Seeder;

class MBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = file_get_contents('./jsonSeeder/bank.json');
        $dataJsonBank = json_decode($data, true);

        foreach ($dataJsonBank as $bank) {
            MBank::create([
                'bank_code' => $bank['code'],
                'bank_name' => $bank['name']
            ]);
        }
    }
}
