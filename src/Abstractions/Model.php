<?php
/**
 * Created by Malik Abiola.
 * Date: 06/02/2016
 * Time: 18:04
 * IDE: PhpStorm
 */

namespace Paystack\Abstractions;

abstract class Model
{
    protected $updateable = false;
    protected $creatable = false;
    protected $deletable = false;

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
     * @param $attributes
     * @return $this
     * @throws \Exception
     */
    public function _setAttributes($attributes)
    {
        if(is_array($attributes) && !empty($attributes)) {
            foreach($attributes as $attribute => $value) {
                $this->{$attribute} = $value;
            }

            return $this;
        }

        //@todo: put real exception here cos exception' gon be thrown either ways, so put one that makes sense
        //or something else that has more meaning
        throw new \Exception();
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
