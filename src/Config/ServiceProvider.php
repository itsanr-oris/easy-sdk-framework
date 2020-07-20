<?php

namespace Foris\Easy\Sdk\Config;

/**
 * Class ServiceProvider
 */
class ServiceProvider extends \Foris\Easy\Sdk\ServiceProvider
{
    /**
     * Register component to application.
     */
    public function register()
    {
        $this->components([Config::class]);
    }
}
