<?php

namespace App\Repository\Plugin;

interface ParameterInterface
{
    /**
     * @return string
     */
    public function getProperty();

    /**
     * @return mixed
     */
    public function getValue();
}