<?php

/**
 * Created by PhpStorm.
 * User: Doctormaliko
 * Date: 01/02/2016
 * Time: 23:07
 */
namespace Paystack;

use Paystack\Contracts\PaystackInterface;

class PaystackLib implements PaystackInterface
{
    private $paystackTransaction;

    public function __construct()
    {
        $this->paystackTransaction = new Transaction();
    }

    /**
     * Initiate transaction on paystack api and get authorization token
     * Function accepts array so as to serve use cases where a post data is passed directly
     * also accepts (amount, email, [plan])
     * @param array $transactionData
     * @return mixed
     */
    public function initiateTransaction(...$transactionData)
    {
        if (
            count($transactionData) == 1 &&
            is_array($transactionData[0]) &&
            $this->validateTransactionData($transactionData[0])
        ) {
            return $this->paystackTransaction->initiate(
                $transactionData[0]['amount'],
                $transactionData[0]['email'],
                $transactionData[0]['plan'] ?: ''
            );
        } else if (count($transactionData) >= 2) {
            return $this->paystackTransaction->initiate(
                $transactionData[0],
                $transactionData[1],
                $transactionData[2] ?: ''
            );
        }
        return null;
    }

    /**
     * Verify status of transaction
     * @param $transactionReference
     * @return mixed
     * @internal param Transaction $transaction
     */
    public function verifyTransaction($transactionReference)
    {
        if (!empty($transactionReference)) {
            return $this->paystackTransaction->details($transactionReference);
        }
        return null;
    }

    /**
     * Checks if transaction data has at least amount and email when array is provided
     * @param $transactionData
     * @return bool
     */
    private function validateTransactionData($transactionData)
    {
        return in_array('amount', $transactionData) && in_array('email', $transactionData);
    }
}
