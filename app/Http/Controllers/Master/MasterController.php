<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\MasterBank\BankService;
use App\Services\Master\MasterCity\CityService;
use App\Services\Master\MasterDistrict\DistrictService;
use App\Services\Master\MasterPostalzip\PostalzipService;
use App\Services\Master\MasterProvince\ProvinceService;
use App\Services\Master\MasterVillage\VillageService;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    private $bankService;
    private $provinceService;
    private $cityService;
    private $districtService;
    private $villageService;
    private $postalZipService;

    /**
     * Class constructor.
     */
    public function __construct(
        BankService $bankService,
        ProvinceService $provinceService,
        CityService $cityService,
        DistrictService $districtService,
        VillageService $villageService,
        PostalzipService $postalZipService
    ) {
        $this->bankService = $bankService;
        $this->provinceService = $provinceService;
        $this->cityService = $cityService;
        $this->districtService = $districtService;
        $this->villageService = $villageService;
        $this->postalZipService = $postalZipService;
    }

    public function getBank()
    {
        $data = $this->bankService->getBank();

        return response()->json($data, 200);
    }

    public function getProvince()
    {
        $data = $this->provinceService->getProvince();

        return response()->json($data, 200);
    }

    public function getCityByProvinceId(Request $req)
    {
        $data = $this->cityService->getCityByIdProvince($req['province_id']);

        return response()->json($data, 200);
    }

    public function getDistrictByCityId(Request $req)
    {
        $data = $this->districtService->getDistrictByIdCity($req['city_id']);

        return response()->json($data, 200);
    }

    public function getVillageByDistrictId(Request $req)
    {
        $data = $this->villageService->getVillageByIdDistrict($req['district_id']);

        return response()->json($data, 200);
    }

    public function getPostalZipByVillageId(Request $req)
    {
        $data = $this->postalZipService->getPostalzipByIdVillage($req['village_id']);

        return response()->json($data, 200);
    }
}
