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
        return $this->customerModel->makeCustomer($firstName, $lastName, $email, $phone)
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
        return $this->customerModel->deleteCustomer($customerId);
    }

    /**
     * @param $amount
     * @param $email
     * @param $plan
     * @return \Exception|mixed
     */
    public function oneTimeTransaction($amount, $email, $plan = '')
    {
        $transaction = $plan instanceof Plan ?
            $this->transactionModel->createOneTimeTransaction($amount, $email, $plan->get('plan_code')) :
            $this->transactionModel->createOneTimeTransaction($amount, $email, $plan);

        return $transaction->charge();
    }

    public function returningTransaction($customerId, $planOrAmount)
    {
        $customer = $this->customerModel->getCustomer($customerId);
        return $this->transactionModel->createReturningCustomerTransaction($customer, $planOrAmount)
            ->charge();
    }

    public function verifyTransaction($transactionRef, $includeCustomer = false)
    {
        return $this->transactionModel->verify($transactionRef, $includeCustomer);
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
