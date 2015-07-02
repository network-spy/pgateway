<?php

namespace PGateway\PaymentSystems;

use PGateway\PaymentSystems\BrainTree\BrainTreePS;

class BrainTreeFactory implements IPaymentSystemFactory
{
    public function FactoryMethod()
    {
        return (new BrainTreePS());
    }
}