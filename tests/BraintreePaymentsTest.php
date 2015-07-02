<?php

require_once '../src/loader.php';

class BrainTreePaymentsTest extends \PHPUnit_Framework_TestCase
{
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = \PGateway\PGatewayFactory::create('BrainTree');
    }

    protected function tearDown()
    {
        $this->fixture = NULL;
    }

    /**
     * @dataProvider providerPayment
     */
    public function testPayment($res, $paymentData)
    {
        $this->assertEquals($res, (bool)$this->fixture->pay($paymentData));
    }

    public function providerPayment()
    {
        $paymentData1 = new \PGateway\PaymentData();
        $paymentData1->setAmount(1000)
            ->setCurrency('USD')
            ->setCustomerFullName('John Wick')
            ->setCCHolderName('John Wick')
            ->setCCExpirationMonth('05')
            ->setCCExpirationYear('2020')
            ->setCCNumber('4111111111111111') // valid number
            ->setCCV2('100');

        $paymentData2 = new \PGateway\PaymentData();
        $paymentData2->setAmount(500)
            ->setCurrency('EUR')
            ->setCustomerFullName('Forrest Gump')
            ->setCCHolderName('Tom Hanks')
            ->setCCExpirationMonth('10')
            ->setCCExpirationYear('2025')
            ->setCCNumber('4111111111111110') //wrong number
            ->setCCV2('123');

        return [
            [true, $paymentData1],
            [false, $paymentData2]
        ];
    }
}