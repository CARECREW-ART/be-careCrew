<?php

namespace App\Http\Controllers\Customer;

use App\Services\User\UserService;
use App\Services\Customer\CustomerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerPostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Customer\MCustomer;
use App\Models\Customer\MCustomerAddress;
use App\Models\Customer\MCustormerPicture;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CustomerController extends Controller
{

    /**
     * Class constructor.
     */

     public function __construct(private UserService $userService)
     {
         $this->userService = $userService;
     }
    
    public function createCustomer(CustomerPostRequest $req) {
        

        $dataValidated = $req->validated();
        $dataEmail = $dataValidated['customer']['customer_email'];
        $dataPassword = $dataValidated['customer']['customer_password'];

        $dataCustomer = $dataValidated['customer'];
        $dataCustomerAddress = $dataValidated['customer_address'];
        $profilePhoto = $dataValidated['customer_picture'];

        try {
            DB::beginTransaction();

            $userId = $this->userService->createUser($dataEmail, $dataPassword, 'customer');

            $customerId = MCustomer::create([
                'user_id' => $userId,
                'customer_fullname' => $dataCustomer['customer_fullname'],
                'customer_nickname' => $dataCustomer['customer_nickname'],
                'customer_username' => $dataCustomer['customer_username'],
                'customer_telp' => $dataCustomer['customer_telp'],
                'customer_gender' => $dataCustomer['customer_gender'],
            ]);

            MCustomerAddress::create([
                'customer_id' => $customerId->customer_id,
                'province_id' => $dataCustomerAddress['province_id'],
                'city_id' => $dataCustomerAddress['city_id'],
                'district_id' => $dataCustomerAddress['district_id'],
                'village_id' => $dataCustomerAddress['village_id'],
                'postalzip_id' => $dataCustomerAddress['postalzip_id'],
                'address_street' => $dataCustomerAddress['address_street'],
                'address_other' => $dataCustomerAddress['address_other']
            ]);

            //Link Photo to Storage
            $photoNameExt = $profilePhoto->getClientOriginalName();
            $extention = $profilePhoto->extension();
            $file_name = (Str::random(16) . '.' . $extention);
            $path = $profilePhoto->move('./storage/photoAssistant', $file_name);
            $url = Storage::url("/photoAssistant/" . $file_name);

            MCustormerPicture::create([
                'customer_id' => $customerId->customer_id,
                'picture_filename' => $file_name,
                'picture_imagename' => $photoNameExt,
                'picture_mime' => $extention,
                'picture_path' => $url
            ]);
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                File::delete($path);
            }

            throw new HttpException(500, $e->getMessage());
        }
    }

    public function getCustomer(Request $req){
        
        return $req;
    }
}
