<?php
/**
 * Created by Malik Abiola.
 * Date: 06/02/2016
 * Time: 18:04
 * IDE: PhpStorm
 */

namespace Paystack\Models;

use Paystack\Contracts\ModelInterface;

abstract class Model implements ModelInterface
{
    protected $updateable = false;
    protected $creatable = false;
    protected $deletable = false;

    abstract public function transform($transformMode);

    abstract public function __setAttributes($attributes);

    /**
     * Get specific model attribute
     * @param  $attributes
     * @return mixed
     */
    public function get($attributes)
    {
        $argsAsArray = func_get_args();
        if (!is_array($attributes) && count($argsAsArray) > 1 ) {
            return call_user_func(array(get_class(), "get"), $argsAsArray);
        }

        if (!is_array($attributes) && count($argsAsArray) == 1 ) {
            return $this->{$attributes} ?: null;
        }

        $attributesGet = [];
        foreach($attributes as $attribute) {
            $attributesGet[$attribute] = $this->{$attribute} ?: null;
        }
        return $attributesGet;
    }

    /**
     * @return boolean
     */
    public function isUpdateable()
    {
        return $this->updateable;
    }

    /**
     * @param boolean $updateable
     */
    public function setUpdateable($updateable)
    {
        $this->updateable = $updateable;
    }

    /**
     * @return boolean
     */
    public function isCreatable()
    {
        return $this->creatable;
    }

    /**
     * @param boolean $creatable
     */
    public function setCreatable($creatable)
    {
        $this->creatable = $creatable;
    }

    /**
     * @return boolean
     */
    public function isDeletable()
    {
        return $this->deletable;
    }

    /**
     * @param boolean $deletable
     */
    public function setDeletable($deletable)
    {
        $this->deletable = $deletable;
    }
}
