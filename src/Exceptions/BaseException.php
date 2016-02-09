<?php
/**
 * Created by Malik Abiola.
 * Date: 08/02/2016
 * Time: 22:21
 * IDE: PhpStorm
 */

namespace Paystack\Exceptions;

abstract class BaseException extends \Exception {
    //put your code here
//
//    public function __construct($response, $code)
//    {
//        parent::__construct($response->message, $code);
//    }

    public function getErrors()
    {

    }
}
