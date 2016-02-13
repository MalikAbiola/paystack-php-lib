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

    /**
     * Get validation errors that occurred in requests
     * @return array
     */
    public function getValidationErrors()
    {
        $errors = [];
        if (($this->response->error)) {
            foreach ($this->response->error as $error => $reason){
                $errors[] = [
                    'attribute' => $error,
                    'reason'    => $reason->message
                ];
            }
        }

        return $errors;
    }
}