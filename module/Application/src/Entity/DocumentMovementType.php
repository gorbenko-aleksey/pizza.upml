<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity;

/**
 * DocumentMovementType
 *
 * @ORM\Table(name="document_movement_type")
 * @ORM\Entity(repositoryClass="Application\Repository\DocumentMovementType")
 */
class DocumentMovementType extends Entity\AbstractEntity implements Entity\EntityNameInterface
{
    /** @var int Тип "Выдача на производство" */
    const WRITE_OFF_ID = 1;

    /** @var int Тип "Возврат на склад */
    const RETURN_ID = 2;

    use Entity\Property\Id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
