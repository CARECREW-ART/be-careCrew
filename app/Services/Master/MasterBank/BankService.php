<?php

namespace App\Services\Master\MasterBank;

use App\Models\Master\MBank;

class BankService
{
    public function getBank()
    {
        $dataBank = MBank::select('bank_id', 'bank_name', 'bank_code')->get();
        return $dataBank;
    }
}
