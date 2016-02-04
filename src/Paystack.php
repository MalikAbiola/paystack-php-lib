<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 23:21
 * IDE: PhpStorm
 */

namespace Paystack;

use Paystack\Factories\PaystackHttpClientFactory;
use Paystack\Models\Customer;
use Paystack\Resources\CustomerResource;

class Paystack
{
    private $paystackHttpClient;
    private $customerModel;

    public function __construct()
    {
        $this->paystackHttpClient = PaystackHttpClientFactory::make();
        $this->customerModel = new Customer(new CustomerResource($this->paystackHttpClient));
    }

    public function getCustomer($customerId)
    {
        return $this->customerModel->getCustomer($customerId)
            ->get();
    }

    public function createCustomer($firstName, $lastName, $email, $phone, $otherAttributes = [])
    {
        return $this->customerModel->makeCustomer($firstName, $lastName, $email, $phone, $otherAttributes)
            ->save();
    }
    public function updateCustomerData($customerId, $updateData)
    {
        return $this->customerModel->getCustomer($customerId)
            ->setUpdateData($updateData)
            ->save()
            ->get();
    }

    public function deleteCustomer($customerId)
    {
        return $this->customerModel->deleteCustomer($customerId);
    }
}
