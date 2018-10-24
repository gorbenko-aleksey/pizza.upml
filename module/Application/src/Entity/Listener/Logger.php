<?php

namespace Application\Entity\Listener;

use App\Entity\EntityInterface;
use Application\Entity\Property;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Zend\Authentication\AuthenticationService;

class Logger implements EventSubscriber
{
    /**
     * @var AuthenticationService
     */
    protected $authenticationService;

    public function __construct(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * Event before update entity
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->log($args->getEntity());
    }

    /**
     * Event before persist entity
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof EntityInterface) {
            $this->log($entity);
        }
    }

    /**
     * Logs the creation or changed time of the entity
     *
     * @param EntityInterface $entity
     */
    protected function log(EntityInterface $entity)
    {
        if (!$this->authenticationService->hasIdentity()) {
            return;
        }

        $user = $this->authenticationService->getIdentity();

        if ($entity->getId()) {
            if ($entity instanceof Property\ChangerInterface) {
                $entity->setChanger($user);
            }
        } else {
            if ($entity instanceof Property\CreatorInterface) {
                $entity->setCreator($user);
            }
        }
    }

    /**
     * Get list of subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::prePersist,
        ];
    }
}
