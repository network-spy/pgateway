<?php

require_once '../src/loader.php';

class CreatePaymentSystemTest extends \PHPUnit_Framework_TestCase
{

    public function testCreatePaymentSystemBrainTree()
    {
        $this->assertInstanceOf('BrainTreePS', \PGateway\PGatewayFactory::create('BrainTree'));
    }


    public function testCreatePaymentSystemPayPal()
    {
        $this->assertInstanceOf('PayPalPS', \PGateway\PGatewayFactory::create('PayPalPS'));
    }

    public function testCreatePaymentSystemUnknown()
    {
        $this->setExpectedException('Exception');
        $paymentSystem = \PGateway\PGatewayFactory::create('Unknown');
    }

}