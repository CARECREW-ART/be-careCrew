<?php

namespace App\Service\Customer;

use App\Models\Customer\MCustomer;
use App\Models\Customer\MCustomerAddress;
use App\Models\Customer\mCustomerPicture;
use App\Services\User\UserService;

class CustomerService
{
   /*
   *Class constructor.
   */

   public function __construct(private UserService $userService)
   {
      $this->userService = $userService;
   }

   public function createCustomer($data){
      $dataCostumer = $data['costumer'];
      $dataCosumerAdrs = $data ['costumer_address'];

   }

   public function getCustomerByUserId($userId)
   {
      $dataCustomer = MCustomer::where('user_id', $userId)->with([
         'mCustomerPicture' => function ($customerPicture)
      {
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
         );
      },
      ])->select(
         'customer_id',
         
      )->first();

   return $dataCustomer;
}
}