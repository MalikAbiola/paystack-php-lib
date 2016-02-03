<?php

namespace Paystack\Models;
use Illuminate\Http\Response;
use Paystack\Contracts\ModelInterface;
use Paystack\ExceptionHandler;
use Paystack\Factories\PaystackHttpClientFactory;

/**
 * Created by Malik Abiola.
 * Date: 03/02/2016
 * Time: 02:54
 * IDE: PhpStorm
 */
class Customer implements ModelInterface
{
    public $paystackHttpClient;
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    public $customerId;

    public function __construct()
    {
        $this->paystackHttpClient = PaystackHttpClientFactory::make();
    }

    /**
     * Make new customer object
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $phone
     * @param string $customerId
     * @return $this
     */
    public function make($firstName, $lastName, $email, $phone, $customerId = '')
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->phone = $phone;
        if (!empty($customerId)) {
            $this->customerId = $customerId;
        }

        return $this;
    }

    /**
     * Get customer object by ID
     * @param $customerId
     * @return Customer
     * @throws \Exception
     */
    public function getCustomerById($customerId)
    {
        $request = $this->paystackHttpClient->get($this->transformUrl($customerId));

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            throw ExceptionHandler::handle($response, $request->getStatusCode());
        }

        $this->customerId = $customerId;

        return $this->make(
            $response->data->first_name,
            $response->data->last_name,
            $response->data->email,
            $response->data->phone
        );
    }

    /**
     * Save New Customer Object
     * @return Customer
     * @throws \Exception
     */
    public function save()
    {
        $request = $this->paystackHttpClient->post(
            $this->transformUrl(),
            [
                'body' => $this->__to(ModelInterface::TRANSFORM_TO_JSON_ARRAY)
            ]
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            throw ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return $this->make(
            $response->data->first_name,
            $response->data->last_name,
            $response->data->email,
            $response->data->phone,
            $response->data->id
        );
    }

    /**
     * Set customer parameters to update
     * @param array $updateData
     * @return $this
     * @todo: possibly refactor this to be more specific instead of having an array that could cause confusion with attribute names
     */
    public function setUpdateData(array $updateData)
    {
       foreach ($updateData as $key => $value) {
           $this->{$key} = $value;
       }

       return $this;
    }

    /**
     * Update Customer Details
     * @return Customer
     * @throws \Exception
     */
    public function update()
    {
        $request = $this->paystackHttpClient->put(
            $this->transformUrl($this->customerId),
            [
                'body' => $this->__to(ModelInterface::TRANSFORM_TO_JSON_ARRAY)
            ]
        );

        $response = json_decode($request->getBody()->getContents());

        if (Response::HTTP_OK !== $request->getStatusCode()) {
            throw ExceptionHandler::handle($response, $request->getStatusCode());
        }

        return $this->make(
            $response->data->first_name,
            $response->data->last_name,
            $response->data->email,
            $response->data->phone,
            $response->data->id
        );
    }
    /**
     * Outward presentation of object
     * @param $transformMode
     * @return mixed
     */
    public function __to($transformMode)
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

    private function transformUrl($customerId = '')
    {
        return str_replace(':id', $customerId, getenv('CUSTOMERS_URL'));
    }
}
