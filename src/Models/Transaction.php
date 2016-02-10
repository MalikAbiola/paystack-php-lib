<?php
/**
 * Created by Malik Abiola.
 * Date: 07/02/2016
 * Time: 15:10
 * IDE: PhpStorm
 */

namespace Paystack\Models;

use Paystack\Abstractions\Model;
use Paystack\Contracts\ModelInterface;
use Paystack\Repositories\TransactionResource;

class Transaction extends Model implements ModelInterface
{
    private function __construct($attributes)
    {
        $this->_setAttributes($attributes);
    }

    public static function make($attributes)
    {
        return new static($attributes);
    }

    public static function details(TransactionResource $transactionResource, $transactionId)
    {
        $transactionData = $transactionResource->get($transactionId);

        if($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        return self::make($transactionData);
    }

    public static function all(TransactionResource $transactionResource, $page)
    {
        $transactions = [];
        $transactionData = $transactionResource->getAll($page);

        if ($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        foreach ($transactionData as $transaction) {
            $transactions[] = self::make($transaction);
        }

        return $transactions;
    }

    public static function totals(TransactionResource $transactionResource)
    {
        return [];
    }


    public function transform($transformMode)
    {
        // TODO: Implement transform() method.
    }
}
