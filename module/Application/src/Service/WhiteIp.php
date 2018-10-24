<?php

namespace Application\Service;

use Zend\Http\PhpEnvironment\RemoteAddress;
use Doctrine\ORM\EntityManager;
use Application\Entity\User as UserEntity;
use Application\Entity\WhiteIp as WhiteIpEntity;
use Application\Repository\WhiteIp as WhiteIpRepository;

class WhiteIp
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @param EntityManager $em
     * @param RemoteAddress $remoteAddress
     */
    public function __construct(EntityManager $em, RemoteAddress $remoteAddress)
    {
        $this->remoteAddress = $remoteAddress;
        $this->em = $em;
    }

    /**
     * Check access for user by ip
     *
     * @param UserEntity $user
     *
     * @return bool
     */
    public function isAllowedByIp(UserEntity $user)
    {
        if (!$user->isCoworker()) {
            return true;
        }

        $result = false;
        if ($ip = $this->remoteAddress->getIpAddress()) {
            foreach ($this->getWhiteIpRepository()->findAll() as $row) {
                $pattern = '/^' . str_replace('*', 'd{1,3}', preg_quote($row->getIp())) . '$/';
                if (preg_match($pattern, $ip)) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * @return WhiteIpRepository
     */
    protected function getWhiteIpRepository()
    {
        return $this->em->getRepository(WhiteIpEntity::class);
    }
}
