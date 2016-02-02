<?php

namespace Paystack\Factories;
/**
 * Description of PaystackHttpClientFactory
 *
 * @author Doctormaliko
 */

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;


class PaystackHttpClientFactory {
    //put your code here
    public static function make($config = [])
    {
        $defaults = [
            'base_uri' => "https://api.paystack.co",
            'headers'     => [
                'Authorization'    => "Bearer " . getenv('PAYSTACK_SECRET_KEY'),
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false,
            'handler'     => HandlerStack::create(new CurlHandler())
        ];

        if (!empty($config)) {
            $defaults = array_merge($defaults, $config);
        }

        return new Client($defaults);
    }
}
