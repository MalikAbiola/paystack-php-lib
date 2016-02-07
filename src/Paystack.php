<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 23:21
 * IDE: PhpStorm
 */

namespace Paystack;

use Paystack\Contracts\TransactionInterface;
use Paystack\Factories\PaystackHttpClientFactory;
use Paystack\Models\Customer;
use Paystack\Models\Plan;
use Paystack\Models\Transaction;
use Paystack\Resources\CustomerResource;
use Paystack\Resources\PlanResource;
use Paystack\Resources\TransactionResource;

class Paystack
{
    private $paystackHttpClient;
    private $customerModel;
    private $transactionModel;
    private $planModel;
    private $customerResource;

    public function __construct()
    {
        $this->paystackHttpClient = PaystackHttpClientFactory::make();

        $this->customerResource = new CustomerResource($this->paystackHttpClient);
        $this->customerModel = new Customer($this->customerResource);

        $transactionResource = new TransactionResource($this->paystackHttpClient);
        $this->transactionModel = new Transaction($transactionResource, $this->customerResource);

        $planResource = new PlanResource($this->paystackHttpClient);
        $this->planModel = new Plan($planResource);
    }

    public function getCustomer($customerId)
    {
        return $this->customerModel->getCustomer($customerId)->transform();
    }

    public function createCustomer($firstName, $lastName, $email, $phone)
    {
        return $this->customerModel->make($firstName, $lastName, $email, $phone)
            ->save()
            ->transform();
    }

    public function updateCustomerData($customerId, $updateData)
    {
        return $this->customerModel->getCustomer($customerId)
            ->setUpdateData($updateData)
            ->save()
            ->transform();
    }

    public function deleteCustomer($customerId)
    {
        return $this->customerModel->getCustomer($customerId)->delete();
    }

    /**
     * @param $amount
     * @param $email
     * @param $plan
     * @return \Exception|mixed
     */
    public function oneTimeTransaction($amount, $email, $plan = '')
    {
        $transactionData = [
            "amount"    => $amount,
            "email"     => $email,
            "plan"      => $plan instanceof Plan ? $plan->get('plan_code') : $plan
        ];

        return $this->transactionModel->make(TransactionInterface::TRANSACTION_TYPE_NEW, $transactionData)->charge();
    }

    public function returningTransaction($customer, $planOrAmount)
    {
        $transactionData = [
            "amount"    => $planOrAmount instanceof Plan ? $planOrAmount->get('amount') : $planOrAmount,
            "email"     => $customer instanceof Customer ? $customer->get('email') : $customer,
            "plan"      => $planOrAmount instanceof Plan ? $planOrAmount->get('name') : null
        ];

        return $this->transactionModel->make(TransactionInterface::TRANSACTION_TYPE_RETURNING, $transactionData)
            ->charge();
    }

    public function verifyTransaction($transactionRef)
    {
        return $this->transactionModel->verifyTransaction($transactionRef);
    }

    public function getPlan($planCode)
    {
        return $this->planModel->getPlan($planCode)->transform();
    }

    public function createPlan($name, $description, $amount, $currency)
    {
        return $this->planModel->make($name, $description, $amount, $currency)
            ->save()
            ->transform();
    }

    public function updatePlan($planCode, $updateData)
    {
        return $this->planModel->getPlan($planCode)
            ->setUpdateData($updateData)
            ->save()
            ->transform();
    }

    public function deletePlan($planCode)
    {
        return $this->planModel->getPlan($planCode)->delete();
    }
}
