<?php

namespace Application\Authentication\Adapter;

use DoctrineModule\Authentication\Adapter\ObjectRepository as BaseObjectRepository;
use Zend\Authentication\Adapter\Exception;
use Zend\Authentication\Result as AuthenticationResult;
use Zend\ServiceManager\ServiceManager;
use Application\Service\WhiteIp as WhiteIpService;
use Application\Authentication\Storage\Session;

class ObjectRepository extends BaseObjectRepository
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Constructor
     *
     * @param array|\DoctrineModule\Options\Authentication $options
     * @param ServiceManager $serviceManager
     */
    public function __construct($options, ServiceManager $serviceManager)
    {
        $this->setOptions($options);
        $this->serviceManager = $serviceManager;
    }

    /**
     * This method abstracts the steps involved with making sure that this adapter was
     * indeed setup properly with all required pieces of information.
     *
     * @throws Exception\RuntimeException - in the event that setup was not done properly
     */
    protected function setup()
    {
        if (null === $this->identity) {
            throw new Exception\RuntimeException(
                'A value for the identity was not provided prior to authentication with ObjectRepository '
                . 'authentication adapter'
            );
        }

        if (!is_object($this->identity) && null === $this->credential) {
            throw new Exception\RuntimeException(
                'A credential value was not provided prior to authentication with ObjectRepository'
                . ' authentication adapter'
            );
        }

        $this->authenticationResultInfo = [
            'code' => AuthenticationResult::FAILURE,
            'identity' => $this->identity,
            'messages' => [],
        ];
    }

    /**
     * This method attempts to validate that the record in the resultset is indeed a
     * record that matched the identity provided to this adapter.
     *
     * @param  object $identity
     * @throws Exception\UnexpectedValueException
     *
     * @return AuthenticationResult
     */
    public function validateIdentity($identity)
    {
        if ($this->credential) {
            return parent::validateIdentity($identity);
        }

        $this->authenticationResultInfo['identity'] = $identity;
        $this->authenticationResultInfo['code'] = AuthenticationResult::SUCCESS;
        $this->authenticationResultInfo['messages'][] = 'Authentication successful.';

        return $this->createAuthenticationResult();
    }

    /*
     * {@inheritDoc}
     */
    public function authenticate()
    {
        $this->setup();
        $options = $this->options;

        if (is_object($this->identity)) {
            $identity = $this->identity;
        } else {
            $identity = $options
                ->getObjectRepository()
                ->findOneBy(array($options->getIdentityProperty() => $this->identity));
        }

        if (!$identity) {
            $this->authenticationResultInfo['code'] = AuthenticationResult::FAILURE_IDENTITY_NOT_FOUND;
            $this->authenticationResultInfo['messages'][] = 'A record with the supplied identity could not be found.';

            return $this->createAuthenticationResult();
        }

        if (!$this->serviceManager->get(WhiteIpService::class)->isAllowedByIp($identity)) {
            $this->authenticationResultInfo['code'] = AuthenticationResult::FAILURE_UNCATEGORIZED;

            return $this->createAuthenticationResult();
        }

        $authResult = $this->validateIdentity($identity);

        return $authResult;
    }
}
