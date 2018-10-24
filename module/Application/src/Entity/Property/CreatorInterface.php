<?php

namespace Application\Entity\Property;

use Application\Entity\User;

interface CreatorInterface
{
    /**
     * @return User
     */
    public function getCreator();

    /**
     * @param User $creator
     *
     * @return $this
     */
    public function setCreator(User $creator);
}
