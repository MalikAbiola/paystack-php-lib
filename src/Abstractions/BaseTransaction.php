<?php
/**
 * Created by Malik Abiola.
 * Date: 13/02/2016
 * Time: 08:30
 * IDE: PhpStorm
 */

namespace Paystack\Abstractions;

use Paystack\Factories\PaystackHttpClientFactory;
use Paystack\Helpers\Utils;
use Paystack\Repositories\TransactionResource;

abstract class BaseTransaction
{
    use Utils;

    protected $transactionResource;

    /**
     * Get set transaction resource
     * @return mixed
     */
    public function getTransactionResource()
    {
        return $this->transactionResource ?: new TransactionResource(PaystackHttpClientFactory::make());
    }

    /**
     * Set transaction resource
     * @param mixed $transactionResource
     */
    public function setTransactionResource(TransactionResource $transactionResource)
    {
        $this->transactionResource = $transactionResource;
    }
}