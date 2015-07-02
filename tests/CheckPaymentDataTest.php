<?php

require_once '../src/loader.php';

class CheckPaymentDataTest extends \PHPUnit_Framework_TestCase
{
    protected $fixture;

    protected function setUp()
    {
        $this->fixture = new \PGateway\PaymentData();
    }

    protected function tearDown()
    {
        $this->fixture = NULL;
    }

    public function testChecking1()
    {
        //empty data
        $this->assertFalse($this->fixture->isValid());
    }

    public function testChecking2()
    {
        $this->fixture->setCCNumber('123') //short credit card number
            ->setCurrency('USD')
            ->setCustomerFullName('John Wick')
            ->setCCHolderName('John Wick')
            ->setCCExpirationMonth('05')
            ->setCCExpirationYear('2020')
            ->setAmount(1000)
            ->setCCV2('100');
        $this->assertFalse($this->fixture->isValid());
    }

    public function testChecking3()
    {
        //all data is valid
        $this->fixture->setAmount(1000)
            ->setCurrency('USD')
            ->setCustomerFullName('John Wick')
            ->setCCHolderName('John Wick')
            ->setCCExpirationMonth('05')
            ->setCCExpirationYear('2020')
            ->setCCNumber('4111111111111111')
            ->setCCV2('100');
        $this->assertTrue($this->fixture->isValid());
    }
}