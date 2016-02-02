<?php
namespace Paystack\Tests;

use GuzzleHttp\Client;
use Paystack\Factories\PaystackHttpClientFactory;
/**
 * Description of PaystackHttpClientFactoryTest
 *
 * @author Doctormaliko
 */
class PaystackHttpClientFactoryTest extends BaseTestCase{
    //put your code here
    public function testPaystackHttpClientReturnsGuzzleClient()
    {
        $this->assertInstanceOf(
            Client::class,
            PaystackHttpClientFactory::make()
        );
    }
}
