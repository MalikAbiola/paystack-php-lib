<?php
/**
 * Created by Malik Abiola.
 * Date: 06/02/2016
 * Time: 15:50
 * IDE: PhpStorm
 */

namespace Paystack\Resources;

use GuzzleHttp\Client;
use Paystack\Contracts\ResourceInterface;

class PlanResource extends Resource implements ResourceInterface
{
    private $paystackHttpClient;

    public function __construct(Client $paystackHttpClient)
    {
        $this->paystackHttpClient = $paystackHttpClient;
    }

    public function get($id)
    {
        $request = $this->paystackHttpClient->get(
            $this->transformUrl(getenv('PLANS_URL'), $id)
        );
        return $this->processResourceRequestResponse($request);
    }

    public function getAll($page = null)
    {
        $request = $this->paystackHttpClient->get(
            $this->transformUrl(getenv('PLANS_URL'), "") . !empty($page) ? "?page = {$page}" : ""
        );

        return $this->processResourceRequestResponse($request);
    }

    public function save($body)
    {
        $request = $this->paystackHttpClient->post(
            $this->transformUrl(getenv('PLANS_URL'), ""),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }

    public function update($id, $body)
    {
        $request = $this->paystackHttpClient->put(
            $this->transformUrl(getenv('PLANS_URL'), $id),
            [
                'body'  => is_array($body) ? $this->toJson($body) : $body
            ]
        );

        return $this->processResourceRequestResponse($request);
    }

    public function delete($id)
    {
        $request = $this->paystackHttpClient->delete(
            $this->transformUrl(getenv('PLANS_URL'), $id)
        );

        return $this->processResourceRequestResponse($request);
    }
}
