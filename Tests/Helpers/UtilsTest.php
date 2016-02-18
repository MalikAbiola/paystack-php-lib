<?php

namespace Paystack\Tests;
use Paystack\Helpers\Utils;

/**
 * Created by Malik Abiola.
 * Date: 15/02/2016
 * Time: 21:42
 * IDE: PhpStorm
 */
class UtilsTest extends BaseTestCase
{
    public function testGenerateTransactionRefIsUnique()
    {
        $this->assertNotEquals(Utils::generateTransactionRef(), Utils::generateTransactionRef());
    }

    public function testGetEnvReturnsEnvValue()
    {
        $this->assertEquals('/transaction/initialize', Utils::env('INITIALIZE_TRANSACTION'));
    }

    public function testGetEnvReturnsDefaultValueWhenKeyNotFound()
    {
        $this->assertEquals("key", Utils::env("NOT_FOUND_KEY", 'key'));
    }

    public function testTransformUrlReturnsTransformedUrl()
    {
        $this->assertEquals('/customer/1', Utils::transformUrl(Utils::env('CUSTOMERS_URL'), 1));
        $this->assertEquals(
            '/transaction/verify/transaction_reference',
            Utils::transformUrl(
                Utils::env('VERIFY_TRANSACTION'),
                'transaction_reference',
                ':reference'
            )
        );
    }
}