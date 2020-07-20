<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk;

/**
 * Class ServiceProvider
 */
class ServiceProvider
{
    /**
     * Application instance.
     *
     * @var Application|ServiceContainer
     */
    protected $container;

    /**
     * ServiceProvider constructor.
     *
     * @param ServiceContainer $container
     */
    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * Get Application instance.
     *
     * @return Application|ServiceContainer
     */
    protected function app()
    {
        return $this->container;
    }

    /**
     * Register component to application.
     */
    public function register()
    {
    }

    /**
     * Register paths to be published by the publish command.
     *
     * @param array $paths
     */
    protected function publishes($paths = [])
    {
        $this->app()->publishes(static::class, $paths);
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app()->get('config')->get($key, []);
        $this->app()->get('config')->set($key, array_merge(require $path, $config));
    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @param array $commands
     */
    protected function commands($commands = [])
    {
        $this->app()->commands($commands);
    }

    /**
     * Register the package's custom sdk components.
     *
     * @param array $components
     */
    protected function components($components = [])
    {
        $this->app()->components($components);
    }
}
