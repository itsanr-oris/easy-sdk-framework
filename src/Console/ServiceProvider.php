<?php

namespace Foris\Easy\Sdk\Console;

use Foris\Easy\Sdk\Console\Commands\ComponentDiscoverCommand;
use Foris\Easy\Sdk\Console\Commands\MakeCommand;
use Foris\Easy\Sdk\Console\Commands\MakeComponentCommand;
use Foris\Easy\Sdk\Console\Commands\PackageDiscoverCommand;
use Foris\Easy\Sdk\Console\Commands\VendorPublishCommand;

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
        $this->app()->singleton('artisan', function () {
            return new Application($this->app());
        });

        $this->app()->singleton('command.make', function () {
            return new MakeCommand();
        });

        $this->app()->singleton('component.make', function () {
            return new MakeComponentCommand();
        });

        $this->app()->singleton('package.discover', function () {
            return new PackageDiscoverCommand();
        });

        $this->app()->singleton('vendor.publish', function () {
            return new VendorPublishCommand();
        });

        $this->app()->singleton('component.discover', function () {
            return new ComponentDiscoverCommand();
        });

        $this->commands(
            ['command.make', 'component.make', 'package.discover', 'vendor.publish', 'component.discover']
        );
    }
}
