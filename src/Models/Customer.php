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

class Customer extends Model
{
    protected $firstName;
    protected $lastName;
    protected $email;
    protected $phone;
    protected $customerId;

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

        $this->setDeletable(true);
        return $this;
    }

    /**
     * set up a new customer object
     * @param $firstName
     * @param $lastName
     * @param $email
     * @param $phone
     * @param array $otherAttributes
     * @return $this
     */
    public function make($firstName, $lastName, $email, $phone, $otherAttributes = [])
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

            $this->__setAttributes($updateAttributes);
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
     * @throws \Exception|mixed
     * @throws null
     */
    public function save()
    {
        $resourceResponse = null;

        if ($this->isCreatable() && !$this->isUpdateable()) { //available for creation
            $resourceResponse = $this->customerResource->save(
                $this->transform(ModelInterface::TRANSFORM_TO_JSON_ARRAY)
            );
        } else if ($this->isUpdateable() && !$this->isCreatable()) { //available for update
            $resourceResponse = $this->customerResource->update(
                $this->customerId,
                $this->transform(ModelInterface::TRANSFORM_TO_JSON_ARRAY)
            );
        }

        if ($resourceResponse == null) {
            throw new \Exception("You Cant Perform This Operation on an empty plan");
        } else if ($resourceResponse instanceof \Exception) {
            throw $resourceResponse;
        }

        return $this->__setAttributes($resourceResponse);
    }

    /**
     * delete customer
     * @return $this
     * @throws \Exception
     * @throws \Exception|mixed
     */
    public function delete()
    {
        if ($this->isDeletable()) {
            $resourceResponse = $this->customerResource->delete($this->customerId);
            if ($resourceResponse instanceof \Exception) {
                throw $resourceResponse;
            }

            return !!$resourceResponse['status'];
        }

        throw new \Exception("Customer could not be deleted");
    }

    /**
     * get Outward presentation of object
     * @param $transformMode
     * @return mixed
     */
    public function transform($transformMode = "")
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
     * @return $this
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

        return $this;
    }
}
