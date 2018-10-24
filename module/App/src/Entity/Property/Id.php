<?php

namespace App\Entity\Property;

use Doctrine\ORM\Mapping as ORM;

trait Id
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":"true"})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Id
     */
    private $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
