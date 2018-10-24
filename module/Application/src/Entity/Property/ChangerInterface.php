<?php

namespace Application\Entity\Property;

use Application\Entity\User;

interface ChangerInterface
{
    /**
     * @return User
     */
    public function getChanger();

    /**
     * @param User $changer
     *
     * @return $this
     */
    public function setChanger(User $changer);
}
