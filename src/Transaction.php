<?php

/**
 * Created by Malik Abiola.
 * Date: 01/02/2016
 * Time: 23:21
 * IDE: PhpStorm
 */

namespace Paystack;

use Illuminate\Http\Response;
use Paystack\Contracts\TransactionInterface;
use Paystack\Factories\PaystackHttpClientFactory;

class Transaction implements TransactionInterface
{
    private $payStackHttpClient;
    private $amount;
    private $customerEmail;
    private $planCode;
    private $transactionReference;

    public function __construct()
    {
        $this->payStackHttpClient = PaystackHttpClientFactory::make();
    }

    /**
     * Set transaction with required data
     * @param $amount
     * @param $customerEmail
     * @param $planCode
     */
    private function setInitializationData($amount, $customerEmail, $planCode)
    {
        $this->customerEmail = $customerEmail;
        $this->amount = $amount;
        $this->planCode = $planCode;
        $this->transactionReference = $this->generateTransactionRef();
    }

    /**
     * Create JSON data to be sent to paystack.co endpoint
     * @return string
     */
    private function toJsonArray()
    {
        $transactionAsArray =  [
            'reference' => $this->transactionReference,
            'amount'    => $this->amount,
            'email'     => $this->customerEmail
        ];

        if (!empty($this->planCode)) {
            $transactionAsArray['plan'] = $this->planCode;
        }

        return json_encode($transactionAsArray);
    }

    /**
     * Generate unique random strings to serve as transaction reference
     * @return string
     */
    private function generateTransactionRef()
    {
//        return str_random(8) .  date('ymdHis');
        return "radnosm" .  date('ymdHis');
    }

    /**
     * Initiate new transaction and get authorization data
     * @param $amount
     * @param $customerEmail
     * @param string $planCode
     * @return mixed
     * @throws \Exception
     */
    public function initiate($amount, $customerEmail, $planCode = '')
    {
        $this->setInitializationData($amount, $customerEmail, $planCode);

        $request = $this->payStackHttpClient->post(
            getenv('INITIALIZE_TRANSACTION'),
            [
                'body'  => $this->toJsonArray()
            ]
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            throw ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return [
            "status"                => true,
            "access_code"           => $response->data->access_code,
            "authorization_url"     => $response->data->authorization_url,
            "transaction_reference" => $this->transactionReference
        ];
    }

    /**
     * Verify transaction / Get transaction Details
     * @param $transactionReference
     * @return mixed
     * @throws \Exception
     */
    public function details($transactionReference)
    {
        $request = $this->payStackHttpClient->get(
            getenv('VERIFY_TRANSACTION') . "/{$transactionReference}"
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            throw ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }
}
