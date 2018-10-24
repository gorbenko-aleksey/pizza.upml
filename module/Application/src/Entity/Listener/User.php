<?php

namespace Application\Entity\Listener;

use Zend\Crypt\Password\Bcrypt;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Application\Entity\User as UserEntity;
use Zend\Session\SessionManager;

class User
{
    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * User constructor.
     *
     * @param SessionManager $sessionManager
     */
    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * Event before persist user
     *
     * @param UserEntity $user
     * @param LifecycleEventArgs $e
     */
    public function prePersist(UserEntity $user, LifecycleEventArgs $e)
    {
        $this->cryptPassword($user);
        $user->createSecret();
    }

    /**
     * Event before update user
     *
     * @param UserEntity $user
     * @param PreUpdateEventArgs $e
     */
    public function preUpdate(UserEntity $user, PreUpdateEventArgs $e)
    {
        if ($e->hasChangedField('password')) {
            $this->cryptPassword(
                $user,
                $e->getNewValue('password'),
                $e->getOldValue('password')
            );

            $sessionId = $this->sessionManager->getId();
            $user->setPasswordChangeSessionId($sessionId);
        }

        $user->createSecret();
    }

    /**
     * Crypt user password
     *
     * @param UserEntity $user
     * @param null $newPassword
     * @param null $oldPassword
     */
    public function cryptPassword(UserEntity $user, $newPassword = null, $oldPassword = null)
    {
        $bcrypt = new Bcrypt(['cost' => 14]);

        if ($user->getId()) {
            if ($newPassword && !$bcrypt->verify($newPassword, $oldPassword)) {
                $user->setPassword($bcrypt->create($newPassword));

                return;
            }

            $user->setPassword($oldPassword);

            return;
        }

        $user->setPassword(
            $bcrypt->create($user->getPassword())
        );
    }
}
