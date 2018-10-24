<?php

namespace App\Form\Validator;

use Zend\Validator\AbstractValidator;

class IpWithMask extends AbstractValidator
{
    /**
     * @var string
     */
    const BAD_VALUE = 'badIpFormat';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::BAD_VALUE => 'Invalid IP format',
    ];

    /**
     * @inheritdoc
     *
     * @return bool
     */
    public function isValid($value)
    {
        $o = '(\d{1,3}|\*)';

        if (!preg_match("/^$o\\.$o\\.$o\\.$o$/", $value)) {
            $this->error(self::BAD_VALUE);

            return false;
        }

        $octets = explode('.', $value);

        foreach ($octets as $octet) {
            if ($octet === '*') {
                continue;
            }

            if ($octet[0] === '0' || $octet > 255) {
                $this->error(self::BAD_VALUE);

                return false;
            }
        }

        return true;
    }
}