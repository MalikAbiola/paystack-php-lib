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
     * @param array $transactionData
     * @return mixed
     */
    public function initiateTransaction(...$transactionData)
    {
        $argCount = func_num_args();
        if ($argCount >= 2){
            $arg_list = func_get_args();
            return $this->paystackTransaction->initiate($arg_list[0], $arg_list[1], $arg_list[2] ?: '');
        } else if ($argCount == 1 && is_array($transactionData) && $this->validateTransactionData($transactionData)) {
            return $this->paystackTransaction->initiate(
                $transactionData['amount'],
                $transactionData['email'],
                $transactionData['plan'] ?: ''
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
