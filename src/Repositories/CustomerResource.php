<?php

/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 22:02
 * IDE: PhpStorm
 */

namespace Paystack\Repositories;

use GuzzleHttp\Client;
use Paystack\Abstractions\Resource;
use Paystack\Contracts\ResourceInterface;
use Paystack\Helpers\Utils;

class CustomerResource extends Resource implements ResourceInterface
{
    use Utils;

    private $paystackHttpClient;

    public function __construct(Client $paystackHttpClient)
    {
        $this->paystackHttpClient = $paystackHttpClient;
    }

    public function get($id)
    {
        $request =  $this->paystackHttpClient->get(
            $this->transformUrl(getenv('CUSTOMERS_URL'), $id)
        );

       return $this->processResourceRequestResponse($request);
    }

    public function getAll($page = null)
    {
        $request =  $this->paystackHttpClient->get(
            $this->transformUrl(getenv('CUSTOMERS_URL'), "") . !empty($page) ? "?page = {$page}" : ""
        );

        return $this->processResourceRequestResponse($request);
    }

    public function save($body)
    {
        $request =  $this->paystackHttpClient->post(
            $this->transformUrl(getenv('CUSTOMERS_URL'), ""),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }

    public function update($id, $body)
    {
        $request =  $this->paystackHttpClient->post(
            $this->transformUrl(getenv('CUSTOMERS_URL'), $id),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }

    public function delete($id)
    {
        $request =  $this->paystackHttpClient->delete(
            $this->transformUrl(getenv('CUSTOMERS_URL'), $id)
        );

        return $this->processResourceRequestResponse($request);
    }
}