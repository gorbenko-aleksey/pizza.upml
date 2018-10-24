<?php

namespace Application\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User;

trait Changer
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="changer_id", referencedColumnName="id")
     */
    private $changer;

    /**
     * @return User
     */
    public function getChanger()
    {
        return $this->changer;
    }

    /**
     * @param User $changer
     *
     * @return $this
     */
    public function setChanger(User $changer)
    {
        $this->changer = $changer;

        return $this;
    }
}
