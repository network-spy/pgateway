<?php

namespace PGateway\PaymentSystems\PayPal;

use PGateway\PaymentSystems\IPaymentSystem;
use PGateway\PaymentData;
use PGateway\Config;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Payer;
//use PayPal\Api\PayerInfo;
use PayPal\Api\Payment;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Transaction;

class PayPalPS implements IPaymentSystem
{

    private $apiContext = null;
    private $errorMessages = [];

    public function __construct()
    {
        $this->apiContext = new ApiContext(new OAuthTokenCredential(
            Config::$paymentSystems['PayPal']['client_id'],
            Config::$paymentSystems['PayPal']['client_secret']
        ));
    }

    public function pay(PaymentData $paymentData)
    {
        $paymentId = null;
        if ($paymentData->isValid()) {
            $holderName = explode(' ', $paymentData->getCCHolderName());
            $holderFirstName = '';
            $holderLastName = '';
            if(count($holderName > 1)) {
                $holderFirstName = $holderName[0];
                $holderName[0] = '';
                $holderName = trim(implode(' ', $holderName));
                $holderLastName = $holderName;
            } else {
                $holderFirstName = $paymentData->getCCHolderName();
            }
            // ### CreditCard
            // A resource representing a credit card that can be
            // used to fund a payment.
            $card = new CreditCard();
            $card->setType($paymentData->getCCType())
                ->setNumber($paymentData->getCCNumber())
                ->setExpireMonth($paymentData->getCCExpirationMonth())
                ->setExpireYear($paymentData->getCCExpirationYear())
                ->setCvv2($paymentData->getCCV2())
                ->setFirstName($holderFirstName)
                ->setLastName($holderLastName);

            // ### FundingInstrument
            // A resource representing a Payer's funding instrument.
            // For direct credit card payments, set the CreditCard
            // field on this object.
            $fi = new FundingInstrument();
            $fi->setCreditCard($card);

            // ### PayerInfo
            // Contains payers info data
            // commented because field email is required but user doesn't fill it in form
            /*
            $payerInfo = new PayerInfo();
            $payerInfo->setFirstName('Name')
                ->setLastName('Surname')
                ->setEmail('login@domain'); //this field is required!
            */

            // ### Payer
            // A resource representing a Payer that funds a payment
            // For direct credit card payments, set payment method
            // to 'credit_card' and add an array of funding instruments.
            $payer = new Payer();
            $payer->setPaymentMethod("credit_card")
                ->setFundingInstruments(array($fi));
                //->setPayerInfo($payerInfo);

            // ### Amount
            // Lets you specify a payment amount.
            // You can also specify additional details
            // such as shipping, tax.
            $amount = new Amount();
            $amount->setCurrency($paymentData->getCurrency())
                ->setTotal($paymentData->getAmount());

            // ### Transaction
            // A transaction defines the contract of a
            // payment - what is the payment for and who
            // is fulfilling it.
            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setDescription("Customer full name: " . $paymentData->getCustomerFullName()) // here is payment description
                ->setInvoiceNumber(uniqid());

            // ### Payment
            // A Payment Resource; create one using
            // the above types and intent set to sale 'sale'
            $payment = new Payment();
            $payment->setIntent("sale")
                ->setPayer($payer)
                ->setTransactions([$transaction]);
            try {
                $payment->create($this->apiContext);
                $paymentId = $payment->getId();
            } catch (\Exception $ex) {
                $this->errorMessages[] = "Error: " . $ex->getMessage() . '<br/> Details: ' . $ex->getData();
            }
        } else {
            $this->errorMessages = $paymentData->getErrorMessages();
        }
        return $paymentId;
    }

    public function getErrors()
    {
        return $this->errorMessages;
    }

}