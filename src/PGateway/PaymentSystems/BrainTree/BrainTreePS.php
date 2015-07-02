<?php

namespace PGateway\PaymentSystems\BrainTree;

use PGateway\Config;
use PGateway\PaymentData;
use PGateway\PaymentSystems\IPaymentSystem;
use Braintree;
use Braintree_Configuration;
use Braintree_Transaction;

class BrainTreePS implements IPaymentSystem {

    private $paymentResult = null;
    private $errorMessages = [];

    public function __construct()
    {
        Braintree_Configuration::environment("sandbox");
        Braintree_Configuration::merchantId(Config::$paymentSystems['BrainTree']['merchantId']);
        Braintree_Configuration::publicKey(Config::$paymentSystems['BrainTree']['publicKey']);
        Braintree_Configuration::privateKey(Config::$paymentSystems['BrainTree']['privateKey']);
    }

    public function pay(PaymentData $paymentData)
    {
        $paymentId = null;
        if ($paymentData->isValid()) {
            $customerName = explode(' ', $paymentData->getCustomerFullName());
            $customerFirstName = '';
            $customerLastName = '';
            if (count($customerName) > 1) {
                $customerFirstName = $customerName[0];
                $customerName[0] = '';
                $customerName = trim(implode(' ', $customerName));
                $customerLastName = $customerName;
            } else {
                $customerFirstName = $paymentData->getCustomerFullName();
            }
            $this->paymentResult = Braintree_Transaction::sale([
                "amount" => $paymentData->getAmount(),
                "creditCard" => [
                    "number" => $paymentData->getCCNumber(),
                    "cvv" => $paymentData->getCCV2(),
                    "expirationMonth" => $paymentData->getCCExpirationMonth(),
                    "expirationYear" => $paymentData->getCCExpirationYear(),
                    "cardholderName" => $paymentData->getCCHolderName()
                ],
                "customer" => [
                    "firstName" => $customerFirstName,
                    "lastName" => $customerLastName
                ],
                "options" => [
                    "submitForSettlement" => true
                ]
            ]);
            if ($this->paymentResult->success) {
                $paymentId = $this->paymentResult->transaction->id; //if success return transaction id
            } else if ($this->paymentResult->transaction) {
                $this->errorMessages[] = 'Error: ' . $this->paymentResult->message .
                    '(Code: ' . $this->paymentResult->transaction->processorResponseCode . ')';
            } else {
                $this->errorMessages[] = "Validation errors:<br/>";
                foreach (($this->paymentResult->errors->deepAll()) as $error) {
                    $this->errorMessages[count($this->errorMessages)-1] .= "\n- " . $error->message . "<br/>";
                }
            }
        } else {
            $this->errorMessages = $paymentData->getErrorMessages();
        }
        return $paymentId;
    }

    public function getPSName()
    {
        return 'BrainTree';
    }

    public function getErrors()
    {
        return $this->errorMessages;
    }
}
