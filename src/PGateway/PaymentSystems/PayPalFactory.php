<?php

namespace PGateway\PaymentSystems;

use PGateway\PaymentSystems\PayPal\PayPalPS;

class PayPalFactory implements IPaymentSystemFactory
{
    public function FactoryMethod()
    {
        return (new PayPalPS());
    }
}