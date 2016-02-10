<?php
/**
 * Created by Malik Abiola.
 * Date: 06/02/2016
 * Time: 16:02
 * IDE: PhpStorm
 */

namespace Paystack\Abstractions;

use Illuminate\Http\Response;
use Paystack\Exceptions\ExceptionHandler;
use Paystack\Helpers\Utils;
use Psr\Http\Message\ResponseInterface;

abstract class Resource
{
    use Utils;
    /**
     * Checks request response and dispatch result to appropriate handler
     * @param ResponseInterface $request
     * @return \Exception|mixed
     */
    public function processResourceRequestResponse(ResponseInterface $request)
    {
        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode() && Response::HTTP_CREATED !== $request->getStatusCode()) {
            return ExceptionHandler::handle(get_class($this), $response, $request->getStatusCode());
        }

        return json_decode(json_encode($response->data), true);
    }
}