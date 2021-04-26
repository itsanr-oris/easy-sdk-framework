<?php

namespace Foris\Easy\Sdk\Traits;

use Foris\Easy\Sdk\Application;

/**
 * Trait HasConfig
 */
trait HasConfig
{
    /**
     * Sdk application instance.
     *
     * @return Application
     */
    abstract protected function app();

    /**
     * Gets the sdk config component instance.
     *
     * @return \Foris\Easy\Sdk\Config\Config
     */
    protected function config()
    {
        return $this->app()->get('config');
    }

    /**
     * Gets the sdk application configuration.
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    protected function getConfig($key, $default = null)
    {
        return $this->config()->get($key, $default);
    }
}
