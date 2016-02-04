<?php

/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 22:02
 * IDE: PhpStorm
 */

namespace Paystack\Resources;

use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Paystack\Contracts\ResourceInterface;
use Paystack\ExceptionHandler;
use Paystack\Helpers\Utils;

class CustomerResource implements ResourceInterface
{
    use Utils;

    private $paystackHttpClient;

    public function __construct(Client $paystackHttpClient)
    {
        $this->paystackHttpClient = $paystackHttpClient;
    }

    public function get($id)
    {
        $request = $this->paystackHttpClient->get(
            $this->transformUrl(getenv('CUSTOMERS_URL'), $id)
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            return ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }

    public function getAll()
    {
        $request = $this->paystackHttpClient->get(
            $this->transformUrl(getenv('CUSTOMERS_URL'), "")
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            return ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }

    public function save($body)
    {
        $request = $this->paystackHttpClient->post(
            $this->transformUrl(getenv('CUSTOMERS_URL'), ""),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            return ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }

    public function update($id, $body)
    {
        $request = $this->paystackHttpClient->post(
            $this->transformUrl(getenv('CUSTOMERS_URL'), $id),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            return ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }

    public function delete($id)
    {
        $request = $this->paystackHttpClient->delete(
            $this->transformUrl(getenv('CUSTOMERS_URL'), $id)
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            return ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }
}