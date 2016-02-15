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

    /**
     * CustomerResource constructor.
     * @param Client $paystackHttpClient
     */
    public function __construct(Client $paystackHttpClient)
    {
        $this->paystackHttpClient = $paystackHttpClient;
    }

    /**
     * Get customer by customer code/id
     * @param $id
     * @return \Exception|mixed
     */
    public function get($id)
    {
        $request =  $this->paystackHttpClient->get(
            $this->transformUrl(self::env('CUSTOMERS_URL'), $id)
        );

       return $this->processResourceRequestResponse($request);
    }

    /**
     * Get all customer. per page.
     * @param null $page
     * @return \Exception|mixed
     */
    public function getAll($page = null)
    {
        $page = !empty($page) ? "/page={$page}" : '';
        $request =  $this->paystackHttpClient->get(
            $this->transformUrl(self::env('CUSTOMERS_URL'), "") . $page
        );

        return $this->processResourceRequestResponse($request);
    }

    /**
     * create new customer
     * @param $body
     * @return \Exception|mixed
     */
    public function save($body)
    {
        $request =  $this->paystackHttpClient->post(
            $this->transformUrl(self::env('CUSTOMERS_URL'), ""),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }

    /**
     * update customer
     * @param $id
     * @param $body
     * @return \Exception|mixed
     */
    public function update($id, $body)
    {
        $request =  $this->paystackHttpClient->put(
            $this->transformUrl(self::env('CUSTOMERS_URL'), $id),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }

    /**
     * delete customer
     * @param $id
     * @return \Exception|mixed
     */
    public function delete($id)
    {
        $request =  $this->paystackHttpClient->delete(
            $this->transformUrl(self::env('CUSTOMERS_URL'), $id)
        );

        return $this->processResourceRequestResponse($request);
    }
}