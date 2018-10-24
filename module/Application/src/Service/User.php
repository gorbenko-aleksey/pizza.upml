<?php

namespace Application\Service;

use Application\Entity\User as UserEntity;

class User
{
    /**
     * Verify user passwords
     *
     * @param UserEntity $user
     * @param string $password
     *
     * @return bool
     */
    public static function verifyCredential(UserEntity $user, $password)
    {
        return password_verify($password, $user->getPassword());
    }
}
