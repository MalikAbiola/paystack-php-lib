<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 22:29
 * IDE: PhpStorm
 */

namespace Paystack\Models;

use Paystack\Contracts\ModelInterface;
use Paystack\Resources\CustomerResource;

class Customer implements ModelInterface
{
    private $customerResource;
    private $updateable = false;
    private $creatable = false;

    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $customerId;

    public function __construct(CustomerResource $customerResource)
    {
        $this->customerResource = $customerResource;
    }

    /**
     * Get customer by ID
     * @param $customerId
     * @return $this
     * @throws \Exception|mixed
     */
    public function getCustomer($customerId)
    {
        //retrieve customer, set customer attributes
        $customerModel = $this->customerResource->get($customerId);
        if ($customerModel instanceof \Exception) {
            throw $customerModel;
        } else {
            $this->__setAttributes($customerModel);
        }

        return $this;
    }

    /**
     * Create a new customer object
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $phone
     * @param array $otherAttributes
     * @return $this
     */
    public function makeCustomer($firstName, $lastName, $email, $phone, $otherAttributes = [])
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;

        if (!empty($otherAttributes)) {
            foreach($otherAttributes as $key => $value) {
                $this->$key = $value;
            }
        }
        //set createable
        $this->setCreatable(true);

        return $this;
    }

    /**
     * set update data on customer model
     * @param $updateAttributes
     * @return $this
     * @throws \Exception
     */
    public function setUpdateData($updateAttributes)
    {
        if (!empty($updateAttributes)) {
            if (isset($updateAttributes['first_name'])) {
                $this->firstName = $updateAttributes['first_name'];
                unset($updateAttributes['first_name']);
            }
            if (isset($updateAttributes['last_name'])) {
                $this->lastName = $updateAttributes['last_name'];
                unset($updateAttributes['last_name']);
            }

            foreach($updateAttributes as $attribute => $value) {
                $this->$attribute = $value;
            }

            //set updateable
            $this->setUpdateable(true);

            return $this;
        } else {
            //replace with more specific exception
            throw new \Exception();
        }
    }

    /**
     * save/update customer model on paystack
     * @return $this
     * @throws \Exception
     */
    public function save()
    {
        $resourceObject = null;

        if (!$this->isCreatable() && !$this->isUpdateable()) {
            throw new \Exception(); //return proper error here
        } else if ($this->isUpdateable() && !$this->isCreatable()) { //available for update
            $resourceObject = $this->customerResource->update($this->customerId, $this->get(ModelInterface::TRANSFORM_TO_JSON_ARRAY));
        } else if (!$this->isUpdateable() && $this->isCreatable()) { //available for creation
            $resourceObject = $this->customerResource->save($this->get(ModelInterface::TRANSFORM_TO_JSON_ARRAY));
        }
        $this->__setAttributes($resourceObject);

        return $this;
    }

    /**
     * get Outward presentation of object
     * @param $transformMode
     * @return mixed
     */
    public function get($transformMode = "")
    {
        switch($transformMode) {
            case ModelInterface::TRANSFORM_TO_JSON_ARRAY:
                return json_encode([
                    'first_name'    => $this->firstName,
                    'last_name'     => $this->lastName,
                    'email'         => $this->email,
                    'phone'         => $this->phone
                ]);
            default:
                return [
                    'first_name'    => $this->firstName,
                    'last_name'     => $this->lastName,
                    'email'         => $this->email,
                    'phone'         => $this->phone
                ];
        }
    }

    /**
     * Set attributes of customer model object
     * @param $attributes
     */
    public function __setAttributes($attributes)
    {
        if (isset($attributes['first_name'])) {
            $this->firstName = $attributes['first_name'];
            unset($attributes['first_name']);
        }
        if (isset($attributes['last_name'])) {
            $this->lastName = $attributes['last_name'];
            unset($attributes['last_name']);
        }
        if (isset($attributes['id'])) {
            $this->lastName = $attributes['id'];
            unset($attributes['id']);
        }

        foreach($attributes as $attribute => $value) {
            $this->$attribute = $value;
        }
    }

    /**
     * delete customer by ID
     * @param $customerId
     * @return $this
     * @throws \Exception|mixed
     */
    public function deleteCustomer($customerId)
    {
        //retrieve customer, set customer attributes
        $customerModel = $this->customerResource->delete($customerId);
        if ($customerModel instanceof \Exception) {
            throw $customerModel;
        }

        return true;
    }
    /**
     * @return boolean
     */
    private function isUpdateable()
    {
        return $this->updateable;
    }

    /**
     * @param boolean $updateable
     */
    private function setUpdateable($updateable)
    {
        $this->updateable = $updateable;
    }

    /**
     * @return boolean
     */
    private function isCreatable()
    {
        return $this->creatable;
    }

    /**
     * @param boolean $creatable
     */
    private function setCreatable($creatable)
    {
        $this->creatable = $creatable;
    }
}
