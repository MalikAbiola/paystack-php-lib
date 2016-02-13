<?php
/**
 * Created by Malik Abiola.
 * Date: 04/02/2016
 * Time: 22:29
 * IDE: PhpStorm
 */

namespace Paystack\Models;

use Paystack\Abstractions\Model;
use Paystack\Contracts\ModelInterface;
use Paystack\Exceptions\PaystackValidationException;
use Paystack\Repositories\CustomerResource;

class Customer extends Model implements ModelInterface
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
        }

        $this->_setAttributes($customerModel);
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

        $this->_setAttributes($otherAttributes);
        //set creatable
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
        if (empty($updateAttributes)) {
            throw new \InvalidArgumentException("Update Attributes Empty");
        }

        $this->_setAttributes($updateAttributes);
        $this->setUpdateable(true);

        return $this;
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
            throw new \InvalidArgumentException("You Cant Perform This Operation on an empty customer object");
        } else if ($resourceResponse instanceof \Exception) {
            throw $resourceResponse;
        }

        return $this->_setAttributes($resourceResponse);
    }

    /**
     * delete customer
     * @return $this
     * @throws \Exception
     * @throws \Exception|mixed
     */
    public function delete()
    {
//        if ($this->isDeletable()) {
//            $resourceResponse = $this->customerResource->delete($this->customerId);
//            if ($resourceResponse instanceof \Exception) {
//                throw $resourceResponse;
//            }
//
//            return !!$resourceResponse['status'];
//        }

        throw new \Exception("Customer can't be deleted");
    }
}
