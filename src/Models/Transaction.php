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

class Transaction extends Model implements ModelInterface
{
    /**
     * Transaction constructor.
     * @param $attributes
     */
    private function __construct($attributes)
    {
        $this->_setAttributes($attributes);
    }

    /**
     * make new transaction object
     * @param $attributes
     * @return static
     */
    public static function make($attributes)
    {
        return new static($attributes);
    }

    /**
     * convert transaction object to array
     * @return mixed
     */
    public function _toArray()
    {
        return $this->transform();
    }
}
