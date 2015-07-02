<?php

namespace PGateway;

class PaymentData
{
    private $amount;
    private $currency;
    private $customerFullName;
    private $ccHolderName;
    private $ccNumber;
    private $ccExpirationMonth;
    private $ccExpirationYear;
    private $ccCCV2;
    private $ccType;

    private $errorMessages = [];
    public static $currencyList = ['USD', 'EUR', 'THB', 'HKD', 'SGD', 'AUD'];

    /**
     *  Detect card type by bumber
     * @see: http://stackoverflow.com/questions/72768/how-do-you-detect-credit-card-type-based-on-number
     */
    public static function getCCTypeByNumber($number)
    {
        if (preg_match('/^3[47][0-9]{5,}$/', $number)) {
            return 'american express';
        } elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{4,}$/',$number)) {
            return 'diners club';
        } elseif (preg_match('/^6(?:011|5[0-9]{2})[0-9]{3,}$/',$number)) {
            return 'discover';
        } elseif (preg_match('/^(?:2131|1800|35[0-9]{3})[0-9]{3,}$/',$number)) {
            return 'jcb';
        } elseif (preg_match('/^5[1-5][0-9]{5,}$/',$number)) {
            return 'mastercard';
        } elseif (preg_match('/^4[0-9]{6,}$/',$number)) {
            return 'visa';
        } else {
            return 'unknown';
        }
    }

    public function __construct(array $paymentData=null)
    {
        if(!empty($paymentData)) {
            $this->amount = !empty($paymentData['amount']) ? : null;
            $this->currency = !empty($paymentData['currency']) ? : null;
            $this->customerFullName = !empty($paymentData['customer_full_name']) ? : null;
            $this->ccHolderName = !empty($paymentData['cc_holder_name']) ? : null;
            $this->ccNumber = !empty($paymentData['cc_number']) ? : null;
            $this->ccExpirationMonth = !empty($paymentData['cc_expiration_month']) ? : null;
            $this->ccExpirationYear = !empty($paymentData['cc_expiration_year']) ? : null;
            $this->ccCCV2 = !empty($paymentData['cc_ccv2']) ? : null;
            $this->ccType = self::getCCTypeByNumber($paymentData['cc_number']);
        }
    }

    public function getAmount()
    {
        return sprintf("%.2f", $this->amount);
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getCustomerFullName()
    {
        return $this->customerFullName;
    }

    public function getCCHolderName()
    {
        return $this->ccHolderName;
    }

    public function getCCNumber()
    {
        return $this->ccNumber;
    }

    public function getCCExpirationMonth()
    {
        return $this->ccExpirationMonth;
    }

    public function getCCExpirationYear()
    {
        return $this->ccExpirationYear;
    }

    public function getCCV2()
    {
        return $this->ccCCV2;
    }

    public function getCCType()
    {
        return $this->ccType;
    }

    public function setAmount($amount)
    {
        $this->amount = sprintf("%.2f", $amount);
        return $this;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function setCustomerFullName($customerFullName)
    {
        $this->customerFullName = $customerFullName;
        return $this;
    }

    public function setCCHolderName($ccHolderName)
    {
        $this->ccHolderName = $ccHolderName;
        return $this;
    }

    public function setCCNumber($ccNumber)
    {
        $this->ccNumber = $ccNumber;
        $this->ccType = self::getCCTypeByNumber($this->ccNumber);
        return $this;
    }

    public function setCCExpirationMonth($ccExpirationMonth)
    {
        $this->ccExpirationMonth = $ccExpirationMonth;
        return $this;
    }

    public function setCCExpirationYear($ccExpirationYear)
    {
        $this->ccExpirationYear = $ccExpirationYear;
        return $this;
    }

    public function setCCV2($cvv2)
    {
        $this->ccCCV2 = $cvv2;
        return $this;
    }
    
    public function isValid()
    {
        if(!is_numeric($this->amount)) {
            $this->errorMessages[] = 'Amount has to be numeric';
        } elseif($this->amount < 0) {
            $this->errorMessages[] = 'Amount has to be more than 0';
        }
        if(!in_array($this->currency, self::$currencyList)) {
            $this->errorMessages[] = 'Wrong currency';
        }
        if(empty($this->customerFullName)) {
            $this->errorMessages[] = 'Customer full name is empty';
        }
        if(empty($this->ccHolderName)) {
            $this->errorMessages[] = 'Credit card holder name is empty';
        }
        if(!ctype_digit($this->ccNumber)) {
            $this->errorMessages[] = 'Credit card number has to be numeric';
        } elseif (strlen($this->ccNumber) < 13) {
            $this->errorMessages[] = 'Wrong credit card number';
        }
        if(!is_numeric($this->ccExpirationMonth)) {
            $this->errorMessages[] = 'Expiration month has to be numeric';
        } elseif ((int)$this->ccExpirationMonth < 1 || (int)$this->ccExpirationMonth > 12) {
            $this->errorMessages[] = 'Wrong expiration month';
        }
        if(!is_numeric($this->ccExpirationYear)) {
            $this->errorMessages[] = 'Expiration year has to be numeric';
        } elseif (strlen($this->ccExpirationYear) != 4) {
            $this->errorMessages[] = 'Wrong expiration year';
        }
        if(!ctype_digit($this->ccCCV2)) {
            $this->errorMessages[] = 'CCV2 has to be numeric';
        } elseif (strlen($this->ccCCV2) < 3 || strlen($this->ccCCV2) > 4) {
            $this->errorMessages[] = 'Wrong CCV2';
        }
        if(empty($this->ccType)) {
            $this->errorMessages[] = 'Credit cards type is empty';
        }
        if(empty($this->errorMessages)) {
            return true;
        }
        return false;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

}