<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 22:17
 * IDE: PhpStorm
 */

namespace Paystack\Helpers;

trait Utils {

    /**
     * Transform url by replacing dummy data
     * @param $url
     * @param $id
     * @return mixed
     */
    public function transformUrl($url, $id)
    {
        return str_replace(':id', $id, $url);
    }

    /**
     * Create Json encoded representation of object
     * @param $object
     * @return string
     */
    public function toJson($object) {
        return json_encode($object);
    }
}