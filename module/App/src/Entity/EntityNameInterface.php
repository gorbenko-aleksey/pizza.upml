<?php

namespace App\Entity;

interface EntityNameInterface
{
    /**
     * @var string
     */
    const PATTERN = '/[a-zA-Z0-9а-яА-Я\p{Sm}\s]/u';

    /**
     * @return string
     */
    public function getName();
}
