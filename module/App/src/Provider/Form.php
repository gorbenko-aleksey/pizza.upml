<?php

namespace App\Provider;

use App\Form\AbstractForm;
use App\Form\FormInterface;

class Form extends AbstractProvider
{
    /**
     * Get form from Form Element Manager
     *
     * @param string $className
     *
     * @return AbstractForm
     */
    public function provide($className)
    {
        if (!class_exists($className)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'The class "%s" was not found',
                $className
            ));
        }

        if (!in_array(FormInterface::class, class_implements($className))) {
            throw new Exception\InvalidArgumentException(sprintf(
                'The class "%s" does not implement "%s" interface',
                $className,
                FormInterface::class
            ));
        }

        return $this->serviceManager->get('FormElementManager')->get($className);
    }
}