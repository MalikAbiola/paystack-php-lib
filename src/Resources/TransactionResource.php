<?php
/**
 * Created by Malik Abiola.
 * Date: 05/02/2016
 * Time: 00:13
 * IDE: PhpStorm
 */

namespace Paystack\Resources;


use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Paystack\ExceptionHandler;
use Paystack\Helpers\Utils;

class TransactionResource
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
            $this->transformUrl(getenv('GET_TRANSACTION'), $id)
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            return ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }

    public function verify($reference)
    {
        $request = $this->paystackHttpClient->get(
            $this->transformUrl(getenv('VERIFY_TRANSACTION'), $reference, ":reference")
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            return ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }

    public function initialize($body)
    {
        $request = $this->paystackHttpClient->post(
            getenv('INITIALIZE_TRANSACTION'),
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

    public function chargeAuthorization($body)
    {
        $request = $this->paystackHttpClient->post(
            getenv('CHARGE_AUTHORIZATION'),
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
}