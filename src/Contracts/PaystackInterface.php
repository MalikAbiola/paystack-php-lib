<?php

/**
 * Created by Malik Abiola.
 * User: Doctormaliko
 * Date: 01/02/2016
 * Time: 23:08
 */
namespace Paystack\Contracts;

use Paystack\Transaction;

interface PaystackInterface
{
    /**
     * Initiate transaction on paystack api and get authorization token
     * @param $input
     * @return mixed
     * @internal param $amount
     * @internal param $customerEmail
     * @internal param string $planCode
     * @internal param Transaction $transaction
     */
    public function initiateTransaction(...$input);

    /**
     * Charge transaction
     * @return mixed
     */
//    public function charge();

    /**
     * Verify status of transaction
     * @param $transactionReference
     * @return mixed
     * @internal param Transaction $transaction
     */
    public function verifyTransaction($transactionReference);
}
