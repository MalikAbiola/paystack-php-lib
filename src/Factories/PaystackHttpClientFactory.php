<?php
/**
 * Created by Malik Abiola.
 * Date: 08/02/2016
 * Time: 22:37
 * IDE: PhpStorm
 * Create Guzzle HTTP Client that handles making requests and all
 */
namespace Paystack\Factories;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;

class PaystackHttpClientFactory {

    public static function make($config = [])
    {
        //determine which mode/key to use
        $authorization = getenv('PAYSTACK_MODE') == 'test' ?
            getenv('PAYSTACK_TEST_SECRET_KEY') :
            getenv('PAYSTACK_LIVE_SECRET_KEY');

        $defaults = [
            'base_uri' => "https://api.paystack.co",
            'headers'     => [
                'Authorization'    => "Bearer " . $authorization,
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false,
            'handler'     => HandlerStack::create(new CurlHandler()) //use native curl
        ];

        if (!empty($config)) {
            $defaults = array_merge($defaults, $config);
        }

        return new Client($defaults);
    }
}
