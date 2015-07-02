<?php

namespace PGateway;

class Config
{
    public static $paymentSystems = [
        'PayPal' => [
            'client_id' => 'AQ_6G__IJz0nUsFCFwHTmHyCbifWfygjYrc3DsUeNvmnXAX4elBjLjXps-PJBCxtP7hpOW2ijwMCewnJ',
            'client_secret' => 'EAufx_FAjlA44SBmbPWMaoar3d2KoiYdOLSZNaeNGa24NVTr3btVAu9Y04NdZCZvbc0lr5WGVJPi8B7d'
        ],
        'BrainTree' => [
            'merchantId' => '9rq4ybnpy5gbhffb',
            'publicKey' => '9qfk73h8gcmtdm2f',
            'privateKey' => 'bc65bb60a90a1aa4f116e97b023fca53'
        ]
    ];

    const defaultPaymentSystem = 'PayPal';
}