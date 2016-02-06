<?php
/**
 * Created by Malik Abiola.
 * Date: 05/02/2016
 * Time: 00:00
 * IDE: PhpStorm
 */

namespace Paystack\Models;

use Paystack\Contracts\ModelInterface;
use Paystack\Helpers\Utils;
use Paystack\Resources\CustomerResource;
use Paystack\Resources\TransactionResource;

class Transaction implements ModelInterface
{
    use Utils;

    private $transactionResource;
    private $customerResource;
    private $newCustomer = false;
    private $returningCustomer = false;

    private $amount;
    private $customerEmail;
    private $planCode;
    private $transactionReference;
    private $authorizationCode;

    public function __construct(TransactionResource $transactionResource, CustomerResource $customerResource)
    {
        $this->transactionResource = $transactionResource;
        $this->customerResource = $customerResource;
    }

    public function createOneTimeTransaction($amount, $customerEmail, $planCode)
    {
        $this->customerEmail = $customerEmail;
        $this->amount = $amount;
        $this->planCode = $planCode;
        $this->transactionReference = $this->generateTransactionRef();
        $this->setNewCustomer(true);

        return $this;
    }

    public function createReturningCustomerTransaction(Customer $customer, $amount)
    {
        $this->customerEmail = $customer->get('email');
        $this->amount = $amount;
        $this->transactionReference = $this->generateTransactionRef();
        $this->authorizationCode = $customer->get('authorization_code');
        $this->setReturningCustomer(true);

        return $this;
    }

    public function charge()
    {
        if (!is_null($this->transactionReference))
        {
            if ($this->isNewCustomer()) {
                //return authorization data
                return $this->transactionResource->initialize($this->__getPayload());
            } else if ($this->isReturningCustomer()) {
                return $this->transactionResource->chargeAuthorization($this->__getPayload());
            }
        }

        return new \Exception(); //@todo: replace with proper error code
    }

    public function verify($reference, $includeCustomer)
    {
        try {
            $transactionData = $this->transactionResource->verify($reference);
            if($includeCustomer) {
                $customerData = $transactionData['customer'];
                $customerData['authorization_code'] = $transactionData['authorization_code'];
                unset($transactionData['customer']);
                $customer = new Customer($this->customerResource);
                $customer->__setAttributes($customerData);
                return [
                    'customer' => $customer,
                    'transaction' => $transactionData
                ];
            }
            return $transactionData;

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function __getPayload()
    {
        $payload = [];
        if ($this->isNewCustomer()) {
            $payload = [
                'amount'    => $this->amount,
                'reference' => $this->transactionReference,
                'email'     => $this->customerEmail
            ];
            if (!empty($this->planCode)) {
                $payload['plan'] = $this->planCode;
            }
        } else if ($this->isReturningCustomer()) {
            $payload = [
                'amount'                => $this->amount,
                'authorization_code'    => $this->authorizationCode,
                'reference'             => $this->transactionReference,
                'email'                 => $this->customerEmail
            ];
        }
        return $this->toJson($payload);
    }
    /**
     * Outward presentation of object
     * @param $transformMode
     * @return mixed
     */
    public function transform($transformMode)
    {
        //@todo
    }

    public function __setAttributes($attributes)
    {
        foreach ($attributes as $attribute => $val) {
            $this->{$attribute} = $val;
        }
    }

    /**
     * @return boolean
     */
    private function isReturningCustomer()
    {
        return $this->returningCustomer;
    }

    /**
     * @param boolean $returningCustomer
     */
    private function setReturningCustomer($returningCustomer)
    {
        $this->returningCustomer = $returningCustomer;
    }

    /**
     * @return boolean
     */
    private function isNewCustomer()
    {
        return $this->newCustomer;
    }

    /**
     * @param boolean $newCustomer
     */
    private function setNewCustomer($newCustomer)
    {
        $this->newCustomer = $newCustomer;
    }

    /**
     * Get specific model attribute
     * @param string $attribute
     * @return mixed
     */
    public function get($attribute = '')
    {
        return $this->{$attribute} ?: new \Exception(); //@todo: return proper error here
    }
}
