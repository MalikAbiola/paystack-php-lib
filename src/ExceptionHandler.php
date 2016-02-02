<?php

/**
 * Created by Malik Abiola.
 * Date: 01/02/2016
 * Time: 23:59
 * IDE: PhpStorm
 */
namespace Paystack;

use Paystack\Exceptions\BaseException;

class ExceptionHandler extends BaseException
{
    public static function handle($e)
    {
        return new \Exception($e->message);

    }
}
