<?php

namespace Foris\Easy\Sdk\Package;

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
        $this->components([PackageManifest::class]);
    }
}
