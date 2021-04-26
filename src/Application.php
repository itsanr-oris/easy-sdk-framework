<?php

namespace Foris\Easy\Sdk;

use Foris\Easy\Sdk\Component\ComponentManifest;
use Foris\Easy\Sdk\Component\ServiceProvider as ComponentServiceProvider;
use Foris\Easy\Sdk\Config\Config;
use Foris\Easy\Sdk\Config\ServiceProvider as ConfigServiceProvider;
use Foris\Easy\Sdk\Console\ServiceProvider as ConsoleServiceProvider;
use Foris\Easy\Sdk\Package\PackageManifest;
use Foris\Easy\Sdk\Package\ServiceProvider as PackageManifestServiceProvider;
use Foris\Easy\Support\Arr;
use Foris\Easy\Support\Str;
use ReflectionClass;

/**
 * Class Application
 */
class Application extends ServiceContainer
{
    /**
     * The easy-sdk framework version.
     *
     * @var string
     */
    const VERSION = '2.0.0';

    /**
     * The root path for the sdk application.
     *
     * @var string
     */
    protected $rootPath;

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Artisan commands.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * The paths that should be published.
     *
     * @var array
     */
    protected $publishes = [];

    /**
     * Application constructor.
     *
     * @param string|array|null $config
     */
    public function __construct($config = null)
    {
        parent::__construct([]);
        $rootPath = is_string($config) ? $config : null;
        $this->setRootPath($rootPath)->mergeConfig(Arr::wrap($config));
    }

    /**
     * Bootstrap the sdk application.
     *
     * @throws \ReflectionException
     */
    protected function bootstrap()
    {
        parent::bootstrap();

        $this->registerProviders($this->get(PackageManifest::name())->providers());
        $this->components($this->get(ComponentManifest::name())->components());
    }

    /**
     * Gets the sdk configuration manager instance.
     *
     * @return Config
     */
    public function config()
    {
        return $this->get('config');
    }

    /**
     * Merge sdk configuration.
     *
     * @param array $config
     * @return Application
     */
    public function mergeConfig($config = [])
    {
        $this->config()->mergeConfig($config);
        return $this;
    }

    /**
     * Return default providers.
     *
     * @return array
     */
    protected function getDefaultProviders()
    {
        $providers = [
            ConfigServiceProvider::class,
            PackageManifestServiceProvider::class,
            ConsoleServiceProvider::class,
            ComponentServiceProvider::class,
        ];

        return array_merge(parent::getDefaultProviders(), $providers);
    }

    /**
     * Set the app root path.
     *
     * @param $rootPath
     * @return $this
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;
        return $this;
    }

    /**
     * Get the app root path.
     *
     * @return bool|string
     * @throws \ReflectionException
     */
    public function getRootPath()
    {
        if (!empty($this->rootPath)) {
            return $this->rootPath;
        }

        $class = new ReflectionClass($this);
        $path  = Str::finish(dirname($class->getFileName()), '/');

        return $this->rootPath = substr($path, 0, strrpos($path, '/src/'));
    }

    /**
     * Gets the file path.
     *
     * @param $file
     * @return string
     * @throws \ReflectionException
     */
    public function getPath($file)
    {
        return $this->getRootPath() . '/' . $file;
    }

    /**
     * Gets the config path.
     *
     * @param $file
     * @return string
     * @throws \ReflectionException
     */
    public function getConfigPath($file)
    {
        return $this->getPath('config/' . $file);
    }

    /**
     * Gets the src path.
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getSrcPath()
    {
        return $this->getPath('src');
    }

    /**
     * Get the root namespace.
     *
     * @return string
     */
    public function getRootNamespace()
    {
        if (!is_null($this->namespace)) {
            return $this->namespace;
        }

        $class = static::class;
        return $this->namespace = substr($class, 0, strrpos($class, '\\'));
    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @param array $commands
     */
    public function commands($commands = [])
    {
        $this->commands = array_merge($this->commands, $commands);
    }

    /**
     * Get artisan commands
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * Register paths to be published by the publish command.
     *
     * @param string $provider
     * @param array  $publishes
     */
    public function publishes($provider, $publishes = [])
    {
        $origin = isset($this->publishes[$provider]) ? $this->publishes[$provider] : [];
        $this->publishes[$provider] = array_merge($origin, $publishes);
    }

    /**
     * Get paths that should be published.
     *
     * @param string|null $provider
     * @return array
     */
    public function getPublishes($provider = null)
    {
        $publishes = isset($this->publishes[$provider]) ? $this->publishes[$provider] : [];
        return $provider === null ? $this->publishes : $publishes;
    }

    /**
     * Register sdk components.
     *
     * @param array $components
     * @throws \ReflectionException
     */
    public function components($components = [])
    {
        foreach ($components as $component) {
            if (!class_exists($component)
                || !is_subclass_of($component, Component::class)) {
                continue;
            }

            $reflect = new ReflectionClass($component);
            if ($reflect->isInstantiable() && $reflect->hasMethod('register')) {
                call_user_func_array([$component, 'register'], [$this]);
            }
        }
    }
}
