<?php

namespace App\Services\Customer;

use App\Exceptions\NotFoundException;
use App\Models\Customer\MCustomer;
use Illuminate\Support\Facades\Storage;

class CustomerService
{
    public function getCustomerAdmin($valueSearch, $valueSort, $sort, $perPage)
    {
        $dataCustomer = MCustomer::with([
            'mCustomerPicture' => function ($CustomerPicture) {
                $CustomerPicture->select(
                    'picture_id',
                    'customer_id',
                    'picture_filename',
                    'picture_path'
                );
            },
            'emailUser' => function ($emailUser) {
                $emailUser->select(
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
            'customer_telp'
        )->where(function ($query) use ($valueSearch) {
            $query->where(
                'customer_fullname',
                'LIKE',
                '%' . $valueSearch . '%'
            )->orWhere(
                'customer_nickname',
                'LIKE',
                '%' . $valueSearch . '%'
            )->orWhere(
                'customer_username',
                'LIKE',
                '%' . $valueSearch . '%'
            )->orWhere(
                'customer_telp',
                'LIKE',
                '%' . $valueSearch . '%'
            );
            return $query;
        });

        if (isset($valueSort) && isset($valueSort)) {
            $dataCustomer = $dataCustomer->orderBy($valueSort, $sort);
        }

        if (isset($perPage)) {
            $dataCustomer = $dataCustomer->latest()->paginate($perPage);
        }

        if ($perPage !== null) {
            $result = $dataCustomer->appends(['sort' => $sort, 'valueSearch' => $valueSearch, 'valueSort' => $valueSort, 'perPage' => $perPage]);
            foreach ($result as $rQuery) {
                if ($rQuery['mCustomerPicture'] == null) {
                    continue;
                }
                $rQuery['mCustomerPicture']['picture_path'] = Storage::url("/photoCustomer/" . $rQuery['mCustomerPicture']['picture_filename']);
            }
            return $result;
        }

        $result = $dataCustomer->latest()->paginate(10)->appends(['sort' => $sort, 'valueSearch' => $valueSearch, 'valueSort' => $valueSort, 'perPage' => $perPage]);
        foreach ($result as $rQuery) {
            if ($rQuery['mCustomerPicture'] == null) {
                continue;
            }
            $rQuery['mCustomerPicture']['picture_path'] = Storage::url("/photoCustomer/" . $rQuery['mCustomerPicture']['picture_filename']);
        }
        return $result;
    }

    public function getCustomerByUserId($userId)
    {
        $dataCustomer = MCustomer::where('user_id', $userId)->with([
            'customerGender' => function ($CustomerGender) {
                $CustomerGender->select(
                    'gender_bit',
                    'gender_value'
                );
            },
            'mCustomerPicture' => function ($CustomerPicture) {
                $CustomerPicture->select(
                    'picture_id',
                    'customer_id',
                    'picture_filename',
                    'picture_path'
                );
            },
            'mCustomerAddress' => function ($CustomerAddress) {
                $CustomerAddress->select(
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

        if ($dataCustomer['mCustomerPicture'] != null) {
            $dataCustomer['mCustomerPicture']['picture_path'] = Storage::url("/photoCustomer/" . $dataCustomer['mCustomerPicture']['picture_filename']);
        }

        $dataCustomer['m_customer_picture'] = null;


        return $dataCustomer;
    }

    public function getCustomerAddressDetail($userId)
    {
        $dataCustomer = MCustomer::where('user_id', $userId)->with([
            'mCustomerAddress' => function ($customerAddress) {
                $customerAddress->with([
                    'mCustomerProvince' => function ($customerProvince) {
                        $customerProvince->select(
                            'province_id',
                            'province_name'
                        );
                    },
                    'mCustomerCity' => function ($customerCity) {
                        $customerCity->select(
                            'city_id',
                            'city_name'
                        );
                    },
                    'mCustomerDistrict' => function ($customerDistrict) {
                        $customerDistrict->select(
                            'district_id',
                            'district_name'
                        );
                    },
                    'mCustomerVillage' => function ($customerVillage) {
                        $customerVillage->select(
                            'village_id',
                            'village_name'
                        );
                    },
                    'mCustomerPostalZip' => function ($customerPostalzip) {
                        $customerPostalzip->select(
                            'postalzip_id',
                            'postalzip_name'
                        );
                    }
                ])->select(
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
        ])->first('customer_id');

        return $dataCustomer;
    }
}
