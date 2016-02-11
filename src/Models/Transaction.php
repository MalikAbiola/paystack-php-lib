<?php
/**
 * Created by Malik Abiola.
 * Date: 07/02/2016
 * Time: 15:10
 * IDE: PhpStorm
 */

namespace Paystack\Models;

use Paystack\Abstractions\Model;
use Paystack\Contracts\ModelInterface;
use Paystack\Helpers\Utils;

class Transaction extends Model implements ModelInterface
{
    use Utils;
    private function __construct($attributes)
    {
        $this->_setAttributes($attributes);
    }

    public static function make($attributes)
    {
        return new static($attributes);
    }

    public function transform($transformMode)
    {
        // TODO: Implement transform() method.
    }

    public function _toArray()
    {
        return $this->objectToArray($this);
    }
}
