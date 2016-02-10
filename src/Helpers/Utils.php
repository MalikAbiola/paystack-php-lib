<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 22:17
 * IDE: PhpStorm
 */

namespace Paystack\Helpers;

use Rhumsaa\Uuid\Exception\UnsatisfiedDependencyException;
use Rhumsaa\Uuid\Uuid;

trait Utils {

    /**
     * Transform url by replacing dummy data
     * @param $url
     * @param $id
     * @return mixed
     */
    public function transformUrl($url, $id, $key = '')
    {
        return str_replace(!empty($key) ? $key : ':id', $id, $url);
    }

    /**
     * Create Json encoded representation of object
     * @param $object
     * @return string
     */
    public function toJson($object) {
        return json_encode($object);
    }

    /**
     * generates a unique transaction ref used for init-ing transactions
     * @return mixed|null
     */
    public function generateTransactionRef()
    {
        try {
            return str_replace("-", "", Uuid::uuid1()->toString());
        } catch (UnsatisfiedDependencyException $e) {
            return null;
        }
    }

    /**
     * Converts a bowl of object to an array.
     * @todo: replace with function that only shows accessible properties of the object
     * @param $object
     * @return array
     */
    public function objectToArray($object)
    {
        if (!is_object($object) && !is_array($object))
        {
            return $object;
        }
        if (is_object($object))
        {
            $object = get_object_vars($object);
        }
        return array_map('objectToArray', $object);
    }
}
