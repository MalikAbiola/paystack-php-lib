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
use Paystack\Models\OneTimeTransaction;
use Paystack\Models\Plan;
use Paystack\Models\ReturningTransaction;
use Paystack\Abstractions\Transaction;
use Paystack\Repositories\CustomerResource;
use Paystack\Repositories\PlanResource;
use Paystack\Repositories\TransactionResource;

class Paystack
{
    private $paystackHttpClient;
    private $customerModel;
    private $planModel;
    private $customerResource;
    private $transactionResource;

    public function __construct()
    {
        $this->paystackHttpClient = PaystackHttpClientFactory::make();

        $this->customerResource = new CustomerResource($this->paystackHttpClient);
        $this->customerModel = new Customer($this->customerResource);

        $this->transactionResource = new TransactionResource($this->paystackHttpClient);

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

    public function startOneTimeTransaction($amount, $email, $plan = '')
    {
        return OneTimeTransaction::make(
            $amount,
            $email,
            $plan instanceof Plan ? $plan->get('plan_code') : $plan
        )->initialize();
    }

    public function startReturningTransaction($authorization, $amount, $email, $plan = '')
    {
        return ReturningTransaction::make(
            $authorization,
            $amount,
            $email,
            $plan instanceof Plan ? $plan->get('plan_code') : $plan
        )->charge();
    }

    public function verifyTransaction($transactionRef)
    {
        return Transaction::verify($transactionRef);
    }

    public function allTransactions($page = '')
    {
        return Transaction::all($page);
    }

    public function myTransactionStats()
    {
        return Transaction::totals();
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
//        return $this->planModel->getPlan($planCode)->delete();
    }
}
