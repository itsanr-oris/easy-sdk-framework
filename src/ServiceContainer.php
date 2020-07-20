<?php /** @noinspection PhpUndefinedClassInspection */

namespace Foris\Easy\Sdk;

use Pimple\Container;

/**
 * Class ServiceContainer
 */
class ServiceContainer extends Container
{
    /**
     * Service provider array.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * ServiceContainer constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($values);
        $this->bootstrap();
    }

    /**
     * Bootstrap the sdk application.
     */
    protected function bootstrap()
    {
        $this->registerProviders($this->getDefaultProviders());
    }

    /**
     * Magic get access.
     *
     * @param string $id
     *
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Magic set access.
     *
     * @param string $id
     * @param mixed  $value
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * Gets the service instance.
     *
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * Register a binding with the container.
     *
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * @param bool $shared
     * @return $this
     */
    public function bind($abstract, $concrete = null, $shared = false)
    {
        $shared ? $this->offsetSet($abstract, $concrete) : $this->offsetSet($abstract, $this->factory($concrete));
        return $this;
    }

    /**
     * Re-register a binding with the container.
     *
     * @param string $abstract
     * @param \Closure|string|null $concrete
     * @param bool $shared
     * @return $this
     */
    public function rebind($abstract, $concrete = null, $shared = false)
    {
        $this->offsetUnset($abstract);
        return $this->bind($abstract, $concrete, $shared);
    }

    /**
     * Register a shared binding in the container.
     *
     * @param  string  $abstract
     * @param  \Closure|string|null  $concrete
     * @return $this
     */
    public function singleton($abstract, $concrete = null)
    {
        return $this->bind($abstract, $concrete, true);
    }

    /**
     * Return all providers.
     *
     * @return array
     */
    protected function getDefaultProviders()
    {
        return $this->providers;
    }

    /**
     * Register all providers
     *
     * @param array $providers
     */
    public function registerProviders(array $providers = [])
    {
        foreach ($providers as $provider) {
            if (!class_exists($provider) || !is_subclass_of($provider, ServiceProvider::class)) {
                continue;
            }

            call_user_func_array([new $provider($this), 'register'], []);
        }
    }
}
