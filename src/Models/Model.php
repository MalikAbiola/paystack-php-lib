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
     * @param string $attribute
     * @return mixed
     * @todo: return proper exception error. see if child class can be gotten from here
     */
    public function get($attribute = '')
    {
        return $this->$attribute ?: new \Exception("Field Not Found");
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