<?php
/**
 * Created by Malik Abiola.
 * Date: 10/02/2016
 * Time: 14:55
 * IDE: PhpStorm
 */

namespace Paystack\Abstractions;

use Paystack\Factories\PaystackHttpClientFactory;
use Paystack\Helpers\Utils;
use Paystack\Models\Transaction as TransactionObject;
use Paystack\Repositories\TransactionResource;

abstract class Transaction
{
    use Utils;

    const TRANSACTION_STATUS_SUCCESS = "success";
    protected $transactionResource;
    protected $paystackHttpClient;

    protected function __construct(TransactionResource $transactionResource)
    {
        $this->transactionResource = $transactionResource;
    }

    /**
     * @return mixed
     */
    public function getTransactionResource()
    {
        return $this->transactionResource;
    }

    abstract protected function _requestPayload();

    public function getType()
    {
        return self::class;
    }

    public static function verify($transactionRef)
    {

        $transactionData = self::getTransactionResource()->verify($transactionRef);
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

    public static function details($transactionId)
    {
        $transactionData = self::getTransactionResource()->get($transactionId);

        if($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        return TransactionObject::make($transactionData);
    }

    public static function all($page)
    {
        $transactions = [];
        $transactionData = self::getTransactionResource()->getAll($page);

        if ($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        foreach ($transactionData as $transaction) {
            $transactions[] = TransactionObject::make($transaction);
        }

        return $transactions;
    }

    /**
     * @todo
     * @return array
     *
     */
    public static function totals()
    {
        return [];
    }
}
