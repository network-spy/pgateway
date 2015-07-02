<?php

namespace PGateway\PaymentSystems;

use PGateway\PaymentData;

interface IPaymentSystem
{
    public function pay(PaymentData $paymentData);
    public function getPSName();
    public function getErrors();
}