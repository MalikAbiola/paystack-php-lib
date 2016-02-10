<?php
/**
 * Created by Malik Abiola.
 * Date: 08/02/2016
 * Time: 22:37
 * IDE: PhpStorm
 */

namespace Paystack\Exceptions;


class PaystackValidationException extends BaseException
{
    private $response;

    public function __construct($response, $code)
    {
        $this->response = $response;
        parent::__construct($response->message, $code);
    }

    public function getValidationErrors()
    {
        $errors = [];
        foreach ($this->response->error as $error => $reason){
            $errors[] = [
                'attribute' => $error,
                'reason'    => $reason->message
            ];
        }
        return $errors;
    }
}