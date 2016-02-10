<?php
/**
 * Created by Malik Abiola.
 * Date: 10/02/2016
 * Time: 14:55
 * IDE: PhpStorm
 */

namespace Paystack\Abstractions;

use Paystack\Helpers\Utils;
use Paystack\Repositories\TransactionResource;

abstract class Transaction
{
    const TRANSACTION_STATUS_SUCCESS = "success";

    use Utils;

    abstract protected function _requestPayload();

    public function getType()
    {
        return self::class;
    }

    public static function verify(TransactionResource $transactionResource, $transactionRef)
    {
        $transactionData = $transactionResource->verify($transactionRef);
        if ($transactionData['status'] == self::TRANSACTION_STATUS_SUCCESS) {
            return [
                'authorization' => $transactionData['authorization'],
                'customer'      => $transactionData['customer'],
                'amount'        => $transactionData['amount'],
                'plan'          => $transactionData['plan']
            ];
        }

        return false;
    }



    public function _toString()
    {

    }

    public function _toArray()
    {
        return $this->objectToArray($this);
    }
}
