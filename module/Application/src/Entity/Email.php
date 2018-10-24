<?php

namespace Application\Entity;

use Zend\Mail\Message;

class Email extends Message
{
    /**
     * @inheritdoc
     */
    protected $encoding = 'UTF-8';
}
