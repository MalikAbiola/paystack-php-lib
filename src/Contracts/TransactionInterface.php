<?php

/**
 * Created by Malik Abiola.
 * Date: 01/02/2016
 * Time: 23:14
 */

namespace Paystack\Contracts;

interface TransactionInterface
{
    /**
     * Initiate new transaction and get authorization code
     * @return mixed
     */
    public function initiate($amount, $customerEmail, $planCode = '');

    /**
     * Verify transaction / Get transaction Details
     * @param string $transactionReference
     * @return mixed
     */
    public function details($transactionReference);
}
