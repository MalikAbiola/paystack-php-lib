<?php
/**
 * Created by Malik Abiola.
 * Date: 05/02/2016
 * Time: 00:13
 * IDE: PhpStorm
 */

namespace Paystack\Resources;

use GuzzleHttp\Client;

class TransactionResource extends Resource
{
    private $paystackHttpClient;

    public function __construct(Client $paystackHttpClient)
    {
        $this->paystackHttpClient = $paystackHttpClient;
    }

    public function get($id)
    {
        $request = $this->paystackHttpClient->get(
            $this->transformUrl(getenv('GET_TRANSACTION'), $id)
        );

        return $this->processResourceRequestResponse($request);
    }

    public function verify($reference)
    {
        $request = $this->paystackHttpClient->get(
            $this->transformUrl(getenv('VERIFY_TRANSACTION'), $reference, ":reference")
        );

        return $this->processResourceRequestResponse($request);
    }

    public function initialize($body)
    {
        $request = $this->paystackHttpClient->post(
            getenv('INITIALIZE_TRANSACTION'),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }

    public function chargeAuthorization($body)
    {
        $request = $this->paystackHttpClient->post(
            getenv('CHARGE_AUTHORIZATION'),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }
}