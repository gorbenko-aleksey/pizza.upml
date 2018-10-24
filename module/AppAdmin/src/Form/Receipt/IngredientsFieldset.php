<?php

namespace AppAdmin\Form\Receipt;

use Application\Entity\ReceiptIngredientWeight;
use Doctrine\ORM\EntityManager;
use Zend\I18n\Translator\TranslatorInterface;

class IngredientsFieldset extends \AppAdmin\Form\IngredientsFieldset
{
    /**
     * IngredientsFieldset constructor.
     *
     * @param EntityManager $em
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $em, TranslatorInterface $translator)
    {
        parent::__construct($em, $translator);

        $this->setObject(new ReceiptIngredientWeight());
    }
}
