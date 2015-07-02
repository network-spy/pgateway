<?php

namespace PGateway;

class Config
{
    public static $paymentSystems = [
        'PayPal' => [
            'client_id' => '',
            'client_secret' => ''
        ],
        'BrainTree' => [
            'merchantId' => '',
            'publicKey' => '',
            'privateKey' => ''
        ]
    ];

    const defaultPaymentSystem = 'PayPal';
}