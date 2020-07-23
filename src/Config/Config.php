<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk\Config;

use Foris\Easy\Sdk\Component;
use Foris\Easy\Support\Arr;
use Foris\Easy\Support\Filesystem;

/**
 * Class Config
 */
class Config extends Component implements \ArrayAccess
{
    /**
     * The configuration path for the sdk application.
     *
     * @var string
     */
    protected $configPath;

    /**
     * All of the configuration items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Get component name
     *
     * @return string
     */
    public static function name()
    {
        return 'config';
    }

    /**
     * Bootstrap the sdk component.
     *
     * @throws \ReflectionException
     */
    protected function bootstrap()
    {
        parent::bootstrap();
        $this->load(Filesystem::scanFiles($this->getConfigPath()));
    }

    /**
     * Set the configuration path.
     *
     * @param $path
     * @return Config
     * @throws \ReflectionException
     */
    public function setConfigPath($path)
    {
        $this->configPath = $path;

        $this->items = [];
        $this->load(Filesystem::scanFiles($this->configPath));

        return $this;
    }

    /**
     * Gets the configuration path.
     *
     * @return string
     * @throws \ReflectionException
     */
    public function getConfigPath()
    {
        if (!empty($this->configPath)) {
            return $this->configPath;
        }

        return $this->configPath = $this->app()->getRootPath() . '/config';
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return Arr::exists($this->items, $key);
    }

    /**
     * Set a given configuration value.
     *
     * @param      $key
     * @param null $value
     * @return Config
     */
    public function set($key, $value = null)
    {
        Arr::set($this->items, $key, $value);
        return $this;
    }

    /**
     * Get the specified configuration value.
     *
     * @param      $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Determine if the given configuration option exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get a configuration option.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set a configuration option.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->set($key, null);
    }

    /**
     * Get configuration key name from the given file.
     *
     * @param $file
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getItemName($file)
    {
        $file = str_replace($this->getConfigPath() . '/', '', $file);
        return str_replace(['/', '\\'], ['.', '.'], substr($file, 0, strpos($file, '.')));
    }

    /**
     * Get configuration from the given file.
     *
     * @param $file
     * @return mixed
     */
    protected function getItemConfig($file)
    {
        return require $file;
    }

    /**
     * Load configuration from specified path.
     *
     * @param $paths
     * @throws \ReflectionException
     */
    public function load($paths)
    {
        $paths = Arr::wrap($paths);

        foreach ($paths as $path) {
            $name = $this->getItemName($path);

            if (empty($name)) {
                continue;
            }

            $config = $this->getItemConfig($path);
            if (is_array($config)) {
                $this->set($name, $config);
            }
        }
    }
}
