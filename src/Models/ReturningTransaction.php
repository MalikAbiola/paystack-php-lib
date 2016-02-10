<?php
/**
 * Created by Malik Abiola.
 * Date: 10/02/2016
 * Time: 16:20
 * IDE: PhpStorm
 */

namespace Paystack\Models;


use Paystack\Abstractions\Transaction;
use Paystack\Exceptions\PaystackInvalidTransactionException;
use Paystack\Factories\PaystackHttpClientFactory;
use Paystack\Repositories\TransactionResource;

class ReturningTransaction extends Transaction
{
    private $transactionResource;

    private $transactionRef;
    private $authorization;
    private $amount;
    private $email;
    private $plan;

    private function __construct($transactionRef, $authorization, $amount, $email, $plan)
    {
        $this->transactionRef = $transactionRef;
        $this->authorization = $authorization;
        $this->amount = $amount;
        $this->email = $email;
        $this->plan = $plan;

        $this->transactionResource = new TransactionResource(PaystackHttpClientFactory::make());
    }

    public static function make($authorization, $amount, $email, $plan)
    {
        return new static(self::generateTransactionRef(), $authorization, $amount, $email, $plan);
    }

    public function charge()
    {
        return !is_null($this->transactionRef) ?
            $this->transactionResource->chargeAuthorization($this->_requestPayload()) :
            new PaystackInvalidTransactionException(["message" => "Transaction Reference Not Generated."]);
    }

    protected function _requestPayload()
    {
        $payload = [
            'authorization_code'    => $this->authorization,
            'amount'                => $this->amount,
            'reference'             => $this->transactionRef,
            'email'                 => $this->email
        ];

        if (!empty($this->plan)) {
            $payload['plan'] = $this->plan;
        }

        return $this->toJson($payload);
    }
}
