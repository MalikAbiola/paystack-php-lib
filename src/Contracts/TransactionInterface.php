<?php
/**
 * Created by Malik Abiola.
 * Date: 07/02/2016
 * Time: 15:16
 * IDE: PhpStorm
 */

namespace Paystack\Contracts;

interface TransactionInterface extends ModelInterface
{
    const TRANSACTION_TYPE_NEW = 1;
    const TRANSACTION_TYPE_RETURNING = 2;

    const TRANSACTION_STATUS_SUCCESS = "success";
}