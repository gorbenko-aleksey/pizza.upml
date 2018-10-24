<?php

namespace App\Entity;

interface EntityCodeInterface
{
    /**
     * @var string
     */
    const PATTERN = '/^[A-Z0-9 _-]+$/i';

    /**
     * @return string
     */
    public function getCode();
}
