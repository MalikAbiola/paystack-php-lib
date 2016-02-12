<?php

/**
 * Created by Malik Abiola.
 * Date: 01/02/2016
 * Time: 23:59
 * IDE: PhpStorm
 */
namespace Paystack\Exceptions;

use Illuminate\Http\Response;

class ExceptionHandler
{
    public static function handle($resourceName, $response, $statusCode)
    {
        switch ($statusCode) {
            case Response::HTTP_UNAUTHORIZED:
                return new PaystackUnauthorizedException($response, $statusCode);
            case Response::HTTP_NOT_FOUND:
                return new PaystackNotFoundException($response, $statusCode);
            case Response::HTTP_BAD_REQUEST:
                return new PaystackValidationException($response, $statusCode);
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                return new \Exception("Internal Server Error.");
            default:
                return new \Exception("Unknown Error Occurred.", $statusCode);
        }
    }
}
