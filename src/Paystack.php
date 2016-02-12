<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 23:21
 * IDE: PhpStorm
 */

namespace Paystack;

use Paystack\Contracts\TransactionContract;
use Paystack\Factories\PaystackHttpClientFactory;
use Paystack\Models\Customer;
use Paystack\Models\OneTimeTransaction;
use Paystack\Models\Plan;
use Paystack\Models\ReturningTransaction;
use Paystack\Models\Transaction;
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
//        return $this->customerModel->getCustomer($customerId)->delete();
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

    public function chargeCustomerForPlan(Customer $customer, Plan $plan)
    {
        return ReturningTransaction::make(
            $customer->get('authorization_code'),
            $plan->get('amount'),
            $customer->get('email'),
            $plan->get('plan_code')
        )->charge();
    }

    public function verifyTransaction($transactionRef)
    {
        $transactionData = $this->getTransactionResource()->verify($transactionRef);
        if ($transactionData['status'] == TransactionContract::TRANSACTION_STATUS_SUCCESS) {
            return [
                'authorization' => $transactionData['authorization'],
                'customer'      => $transactionData['customer'],
                'amount'        => $transactionData['amount'],
                'plan'          => $transactionData['plan']
            ];
        }

        return false;
    }

    public function transactionDetails($transactionId)
    {
        $transactionData = $this->getTransactionResource()->get($transactionId);

        if($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        return Transaction::make($transactionData);
    }

    public function allTransactions($page)
    {
        $transactions = [];
        $transactionData = $this->getTransactionResource()->getAll($page);

        if ($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        foreach ($transactionData as $transaction) {
            $transactions[] = Transaction::make($transaction);
        }

        return $transactions;
    }

    /**
     * @todo
     * @return array
     *
     */
    public function transactionsTotals()
    {
        return $this->getTransactionResource()->getTransactionTotals();
    }

    /**
     * @return TransactionResource
     */
    public function getTransactionResource()
    {
        return $this->transactionResource;
    }

    /**
     * @param TransactionResource $transactionResource
     */
    public function setTransactionResource($transactionResource)
    {
        $this->transactionResource = $transactionResource;
    }

    /**
     * @return CustomerResource
     */
    public function getCustomerResource()
    {
        return $this->customerResource;
    }

    /**
     * @param CustomerResource $customerResource
     */
    public function setCustomerResource($customerResource)
    {
        $this->customerResource = $customerResource;
    }

    /**
     * @return \GuzzleHttp\Client
     */
    public function getPaystackHttpClient()
    {
        return $this->paystackHttpClient;
    }

    /**
     * @param \GuzzleHttp\Client $paystackHttpClient
     */
    public function setPaystackHttpClient($paystackHttpClient)
    {
        $this->paystackHttpClient = $paystackHttpClient;
    }
}
