<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

class PluginManager
{
    /**
     * Plugin\PluginInterface[]
     *
     * @var array
     */
    protected $plugins = [];

    /**
     * Register new plugin by name
     *
     * @param string $alias
     * @param Plugin\PluginInterface $plugin
     *
     * @return $this
     */
    public function register($alias, Plugin\PluginInterface $plugin)
    {
        $this->plugins[$alias] = $plugin;

        return $this;
    }

    /**
     * Checks if the plugin is registered
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return isset($this->plugins[$name]);
    }

    /**
     * Gets plugin by name if it exists
     *
     * @param string $name
     *
     * @throws Exception\BadFunctionCallException
     *
     * @return AbstractPlugin
     */
    public function get($name)
    {
        if (!$this->has($name)) {
            throw new Exception\BadFunctionCallException(sprintf(
                'Plugin with name "%s" was not registered',
                $name
            ));
        }

        return $this->plugins[$name];
    }

    /**
     * Apply all plugins
     *
     * @param QueryBuilder $qb
     * @param array $parameters
     * @param array $aliases
     */
    public function apply(QueryBuilder $qb, array $parameters, array $aliases = [])
    {
        foreach ($this->plugins as $alias => $plugin) {
            if (!empty($aliases) && !in_array($alias, $aliases)) {
                continue;
            }

            $plugin->apply($qb, $parameters);
        }
    }
}