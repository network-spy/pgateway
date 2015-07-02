<?php

namespace PGateway;

use PGateway\Config;

class PGatewayFactory
{
    public static function create($paymentSystemName=Config::defaultPaymentSystem)
    {
        if (!empty($paymentSystemName) && in_array($paymentSystemName, array_keys(Config::$paymentSystems))) {
            if($paymentSystemClassName = self::getPaymentSystemClassName($paymentSystemName)) {
                return (new $paymentSystemClassName)->FactoryMethod();
            }
        }
        throw new \Exception("Wrong payment system name!");
    }

    protected static function getPaymentSystemClassName($paymentSystemName)
    {
        $paymentClass = 'PGateway\\' . sprintf('PaymentSystems\%s', $paymentSystemName.'Factory');
        if (class_exists($paymentClass, true)) {
            return $paymentClass;
        }
        return null;
    }
}