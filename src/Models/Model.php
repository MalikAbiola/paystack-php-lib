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
    private $updateable = false;
    private $creatable = false;
    private $deleteable = false;

    abstract public function transform($transformMode);

    abstract public function __setAttributes($attributes);

    /**
     * Get specific model attribute
     * @param  $attributes
     * @return mixed
     */
    public function get($attributes)
    {
        if (!is_array($attributes)) {
            $argsAsArray = func_get_args();
            $attributesGet = [];
            foreach($argsAsArray as $attribute) {
                $attributesGet[$attribute] = $this->{$attribute} ?: null;
            }
            return $attributesGet;
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
    public function isDeleteable()
    {
        return $this->deleteable;
    }

    /**
     * @param boolean $deleteable
     */
    public function setDeleteable($deleteable)
    {
        $this->deleteable = $deleteable;
    }
}