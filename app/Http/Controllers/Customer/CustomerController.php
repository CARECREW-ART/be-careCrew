<?php

namespace App\Http\Controllers\Customer;

use App\Exceptions\NotFoundException;
use App\Services\User\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerAddressPutRequest;
use App\Http\Requests\Customer\CustomerPicturePutRequest;
use App\Http\Requests\Customer\CustomerPostRequest;
use App\Http\Requests\Customer\CustomerPutRequest;
use App\Http\Requests\User\UserPasswordPutRequest as PasswordChange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Customer\MCustomer;
use App\Models\Customer\MCustomerAddress;
use App\Models\Customer\MCustormerPicture;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{

    /**
     * Class constructor.
     */

    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createCustomer(CustomerPostRequest $req)
    {


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
                'customer_birthdate' => $dataCustomer['customer_birthdate']
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
            $path = $profilePhoto->move('./storage/photoCustomer', $file_name);
            $url = Storage::url("/photocustomer/" . $file_name);

            MCustormerPicture::create([
                'customer_id' => $customerId->customer_id,
                'picture_filename' => $file_name,
                'picture_imagename' => $photoNameExt,
                'picture_mime' => $extention,
                'picture_path' => $url
            ]);
            DB::commit();

            return response()->json(['message' => 'data berhasil ditambahkan'], 201);
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                File::delete($path);
            }

            if ($e instanceof QueryException) {
                return response()->json(['message' => $e->getMessage()], 501);
            }

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getCustomerByUserId()
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataCustomer = MCustomer::where('user_id', $userId)->with([
            'customerGender' => function ($customerGender) {
                $customerGender->select(
                    'gender_bit',
                    'gender_value'
                );
            },
            'mCustomerPicture' => function ($customerPicture) {
                $customerPicture->select(
                    'picture_id',
                    'customer_id',
                    'picture_filename',
                    'picture_path'
                );
            },
            'mCustomerAddress' => function ($customerAddress) {
                $customerAddress->select(
                    'address_id',
                    'customer_id',
                    'province_id',
                    'city_id',
                    'district_id',
                    'village_id',
                    'postalzip_id',
                    'address_street',
                    'address_other'
                );
            },
            'emailUser' => function ($email) {
                $email->select(
                    'user_id',
                    'email'
                );
            },
        ])->select(
            'customer_id',
            'user_id',
            'customer_fullname',
            'customer_nickname',
            'customer_username',
            'customer_telp',
            'customer_gender',
            'customer_birthdate',
        )->first();

        if ($dataCustomer == null) {
            throw new NotFoundException('Data Customer Tidak Ada');
        }

        return response()->json(['data' => $dataCustomer], 200);
    }

    public function putDetailCustomer(CustomerPutRequest $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataValid = $req->validated();

        [$data, $message] = $this->userService->verifyUserValidPassword($userId, $dataValid['password']);

        if (!$data) {
            return response()->json(['message' => $message], 400);
        }

        try {
            DB::beginTransaction();
            $dataCustomer = MCustomer::where('user_id', $data);
            $dataCustomer->update($dataValid['customer']);
            DB::commit();

            return response()->json(['message' => 'data customer berhasil diupdate'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function putCustomerAddressByUserId(CustomerAddressPutRequest $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataValid = $req->validated();

        [$data, $message] = $this->userService->verifyUserValidPassword($userId, $dataValid['password']);

        if (!$data) {
            return response()->json(['message' => $message], 400);
        }

        try {
            DB::beginTransaction();
            $customerId = MCustomer::where('user_id', $userId)->first();

            if ($customerId == null) {
                return response()->json(['message' => 'data customer tidak ada'], 404);
            }

            $dataAssistantAddrs = MCustomerAddress::where('customer_id', $customerId->customer_id);

            $dataAssistantAddrs->update($dataValid['customer_address']);

            DB::commit();

            return response()->json(['message' => 'data customer berhasil diupdate']);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function putCustomerPictureByUserId(CustomerPicturePutRequest $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataValid = $req->validated();

        $dataCustomer = MCustomer::where('user_id', $userId)->with([
            'customerGender' => function ($customerGender) {
                $customerGender->select(
                    'gender_bit',
                    'gender_value'
                );
            },
            'mCustomerPicture' => function ($customerPicture) {
                $customerPicture->select(
                    'picture_id',
                    'customer_id',
                    'picture_filename',
                    'picture_path'
                );
            },
            'mCustomerAddress' => function ($customerAddress) {
                $customerAddress->select(
                    'address_id',
                    'customer_id',
                    'province_id',
                    'city_id',
                    'district_id',
                    'village_id',
                    'postalzip_id',
                    'address_street',
                    'address_other'
                );
            },
            'emailUser' => function ($email) {
                $email->select(
                    'user_id',
                    'email'
                );
            },
        ])->select(
            'customer_id',
            'user_id',
            'customer_fullname',
            'customer_nickname',
            'customer_username',
            'customer_telp',
            'customer_gender',
            'customer_birthdate',
        )->first();

        $dataCustomerPicture = $dataCustomer->mCustomerPicture;

        $pathOldPhoto = './storage/photocustomer/' . $dataCustomerPicture->picture_filename;

        try {
            DB::beginTransaction();

            //Link Photo to Storage
            $dataPhoto = $dataValid['customer_picture'];
            $photoNameExt = $dataPhoto->getClientOriginalName();
            $extension = $dataPhoto->extension();
            $file_name = (Str::random(16) . '.' . $extension);
            $path = $dataPhoto->move('./storage/photocustomer', $file_name);
            $url = Storage::url("/photocustomer/" . $file_name);

            MCustormerPicture::create([
                'customer_id' => $dataCustomer->customer_id,
                'picture_filename' => $file_name,
                'picture_imagename' => $photoNameExt,
                'picture_mime' => $extension,
                'picture_path' => $url
            ]);

            if (isset($pathOldPhoto)) {
                File::delete($pathOldPhoto);
            }

            $dataOldPhoto = MCustormerPicture::where('picture_filename', $dataCustomerPicture->picture_filename);
            $dataOldPhoto->delete();

            DB::commit();

            return response()->json(['message' => 'data profile picture berhasil diperbaharui'], 201);
        } catch (Exception $e) {
            DB::rollBack();

            if (isset($path)) {
                File::delete($path);
            }

            throw new Exception($e->getMessage());
        }
    }

    public function putCustomerPassword(PasswordChange $req)
    {
        $userId = auth('sanctum')->user()->user_id;

        $dataValidated = $req->validated();

        $this->userService->changePasswordUser($userId, $dataValidated['new_password'], $dataValidated['old_password']);

        return response()->json(['message' => 'password berhasil diganti'], 200);
    }
}
