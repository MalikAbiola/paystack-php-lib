<?php
/**
 * Created by Malik Abiola.
 * Date: 16/02/2016
 * Time: 11:46
 * IDE: PhpStorm
 */

namespace Paystack\Helpers;


use Paystack\Abstractions\BaseTransaction;
use Paystack\Contracts\TransactionContract;
use Paystack\Models\Transaction as TransactionObject;

class Transaction extends BaseTransaction
{
    public static function make()
    {
        return new static;
    }

    /**
     * Verify Transaction
     * @param $transactionRef
     * @return array|bool
     * @throws \Exception
     */
    public function verify($transactionRef)
    {
        $transactionData = $this->getTransactionResource()->verify($transactionRef);

        if ($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        if ($transactionData['status'] == TransactionContract::TRANSACTION_STATUS_SUCCESS) {
            return [
                'authorization' => $transactionData['authorization'],
                'customer'      => $transactionData['customer'],
                'amount'        => $transactionData['amount'],
                'plan'          => $transactionData['plan']
            ];
        }

        return false;
    }
    /**
     * Get transaction details
     * @param $transactionId
     * @return \Paystack\Models\Transaction
     * @throws \Exception|mixed
     */
    public function details($transactionId)
    {
        $transactionData = $this->getTransactionResource()->get($transactionId);

        if($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        return TransactionObject::make($transactionData);
    }

    /**
     * Get all transactions. per page
     * @param $page
     * @return array
     * @throws \Exception|mixed
     */
    public function allTransactions($page)
    {
        $transactions = [];
        $transactionData = $this->getTransactionResource()->getAll($page);

        if ($transactionData instanceof \Exception) {
            throw $transactionData;
        }

        foreach ($transactionData as $transaction) {
            $transactions[] = TransactionObject::make($transaction);
        }

        return $transactions;
    }

    /**
     * Get merchant transaction total
     * @return mixed
     * @throws \Exception
     */
    public function transactionsTotals()
    {
        $transactions = $this->getTransactionResource()->getTransactionTotals();
        if ($transactions instanceof \Exception) {
            throw $transactions;
        }

        return $transactions;
    }
}