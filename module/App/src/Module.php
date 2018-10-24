<?php

namespace App;

use Doctrine\DBAL\Types\Type;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use Zend\ServiceManager\ServiceLocatorInterface;
use App\Doctrine\DoctrineExtensions\DBAL\Types\UTCDateTimeType;
use Zend\Console;

class Module implements Feature\ConfigProviderInterface, Feature\BootstrapListenerInterface
{
    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $sm = $e->getApplication()->getServiceManager();

        $this->initServices($sm);
        $this->initTimezone($sm->get('config'));
        $this->initDoctrineExtensions($sm);
        $this->clearCache($sm);
    }

    /**
     * Init services
     *
     * @param ServiceLocatorInterface $sm
     */
    protected function initServices(ServiceLocatorInterface $sm)
    {
        /* init sentry service */
        $sm->get(Service\Sentry::class);
    }

    /**
     * Init application timezone
     *
     * @param array $config
     */
    protected function initTimezone(array $config)
    {
        if (!empty($config['time_zone'])) {
            date_default_timezone_set($config['time_zone']);
        }
    }

    /**
     * Init doctrine extensions
     *
     * @param ServiceLocatorInterface $sm
     */
    protected function initDoctrineExtensions(ServiceLocatorInterface $sm)
    {
        Type::overrideType('datetime', UTCDateTimeType::class);
    }

    /**
     * Clear cache
     *
     * @param ServiceLocatorInterface $sm
     */
    protected function clearCache(ServiceLocatorInterface $sm)
    {
        $config = $sm->get('config');
        $request = $sm->get('request');

        $keyCaches = 'caches';

        if (!Console\Console::isConsole() && $request->getQuery('clear') === 'cache') {
            if (array_key_exists($keyCaches, $config) && is_array($config[$keyCaches])) {
                foreach ($config[$keyCaches] as $cacheName => $cacheParams) {
                    $cache = $sm->get($cacheName);
                    $cache->flush();
                }
            }
        }
    }
}
