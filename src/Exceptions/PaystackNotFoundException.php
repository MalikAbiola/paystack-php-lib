<?php
/**
 * Created by Malik Abiola.
 * Date: 12/02/2016
 * Time: 22:02
 * IDE: PhpStorm
 */

namespace Paystack\Exceptions;


class PaystackNotFoundException extends BaseException
{
    public function __construct($response, $code)
{
    parent::__construct($response->message, $code);
}
}
