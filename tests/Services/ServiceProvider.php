<?php

namespace Foris\Easy\Sdk\Tests\Services;

/**
 * Class ServiceProvider
 */
class ServiceProvider extends \Foris\Easy\Sdk\ServiceProvider
{
    /**
     * Register service to container.
     */
    public function register()
    {
        $this->commands($this->getCommands());

        $this->publishes($this->getPublishableAssets());

        $this->components($this->getComponents());

        $this->app()->bind('helloService', function () {
            return new HelloService();
        });

        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'test-config');
    }

    /**
     * Gets the commands.
     *
     * @return array
     */
    public function getCommands()
    {
        return ['test.command'];
    }

    /**
     * Gets the publishable assets.
     *
     * @return array
     */
    public function getPublishableAssets()
    {
        return ['from' => 'to'];
    }

    /**
     * Gets the components.
     *
     * @return array
     */
    public function getComponents()
    {
        return ['NotExistsComponent', HelloService::class, HelloComponent::class];
    }
}
