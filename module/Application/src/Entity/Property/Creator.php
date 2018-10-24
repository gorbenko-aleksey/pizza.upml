<?php

namespace Application\Entity\Property;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User;

trait Creator
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    private $creator;

    /**
     * @return User
     */
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     *
     * @return $this
     */
    public function setCreator(User $creator)
    {
        $this->creator = $creator;

        return $this;
    }
}
