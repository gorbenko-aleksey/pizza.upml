<?php

namespace App\Service;

class Sentry
{
    /**
     * Sentry client
     *
     * @var \Raven_Client
     */
    protected $sentryClient;

    /**
     * Call
     *
     * @param string $name
     * @param array $arguments
     */
    public function __call($name, array $arguments)
    {
        if (method_exists($this->sentryClient, $name)) {
            call_user_func_array([$this->sentryClient, $name], $arguments);
        }
    }

    /**
     * Constructor
     *
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $config = @$config['sentry']['php'];

        if (empty($config['enable']) || !$config['enable']) {
            return;
        }

        if (empty($config['dsn']) || !is_string($config['dsn'])) {
            throw new \Exception('Dsn option in sentry config not found!');
        }

        $this->sentryClient = new \Raven_Client($config['dsn']);

        if (!empty($config['release'])) {
            $this->sentryClient->setRelease($config['release']);
        }

        if (!empty($config['environment'])) {
            $this->sentryClient->setEnvironment($config['environment']);
        }

        $this->sentryClient->install();
    }
}
