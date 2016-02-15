<?php
/**
 * Created by Malik Abiola.
 * Date: 15/02/2016
 * Time: 17:32
 * IDE: PhpStorm
 */

namespace Paystack\Tests;


use Paystack\Exceptions\PaystackInvalidTransactionException;
use Paystack\Factories\PaystackHttpClientFactory;
use Paystack\Models\OneTimeTransaction;
use Paystack\Repositories\TransactionResource;

class OneTimeTransactionTest extends BaseTestCase
{
    private $transactionResource;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->transactionResource = new TransactionResource(PaystackHttpClientFactory::make());
    }

    public function testInitializeOneTimeTransaction()
    {
        $mockTransactionResource = \Mockery::mock($this->transactionResource)->makePartial();
        $mockTransactionResource->shouldReceive('initialize')
            ->once()
            ->andReturn($this->initOneTimeTransactionResourceResponseData);

        $oneTimeTransaction = OneTimeTransaction::make($this->planData['amount'], $this->customerData['email'], '');
        $oneTimeTransaction->setTransactionResource($mockTransactionResource);
        $initOneTimeTransaction = $oneTimeTransaction->initialize();

        $this->assertEquals($this->initOneTimeTransactionResourceResponseData, $initOneTimeTransaction);
    }


    public function tearDown()
    {
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}