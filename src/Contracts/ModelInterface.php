<?php
/**
 * Created by Malik Abiola.
 * Date: 03/02/2016
 * Time: 03:07
 * IDE: PhpStorm
 */

namespace Paystack\Contracts;

interface ModelInterface
{
    const TRANSFORM_TO_JSON_ARRAY = 1;
    const TRANSFORM_TO_ARRAY = 2;
    const TRANSFORM_TO_STRING = 3;

    /**
     * Outward presentation of object
     * @param $transformMode
     * @return mixed
     */
    public function __to($transformMode);
}