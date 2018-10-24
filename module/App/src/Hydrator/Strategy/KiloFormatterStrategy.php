<?php

namespace App\Hydrator\Strategy;

use Zend\Hydrator\Strategy\StrategyInterface;

class KiloFormatterStrategy implements StrategyInterface
{
    /**
     * {@inheritDoc}
     *
     * Converts given value in gram to value in kilogram.
     *
     * @param float $value The original value.
     *
     * @return float
     */
    public function extract($value)
    {
        return is_null($value) ? $value : $value / 1000;
    }

    /**
     * {@inheritDoc}
     *
     * Converts given value in kilogram to value in gram.
     *
     * @param float $value
     *
     * @return float
     */
    public function hydrate($value)
    {
        return $value * 1000;
    }
}
