<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk\Component;

use Exception;
use Foris\Easy\Sdk\Application;
use Foris\Easy\Sdk\Component;
use Foris\Easy\Support\Filesystem;
use ReflectionClass;

/**
 * Class ComponentManifest
 */
class ComponentManifest extends Component
{
    /**
     * The base path.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The manifest path.
     *
     * @var string|null
     */
    protected $manifestPath;

    /**
     * The loaded manifest array.
     *
     * @var array
     */
    protected $manifest;

    /**
     * ComponentManifest constructor.
     *
     * @param Application $app
     * @throws \ReflectionException
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->basePath = $app->getRootPath();
        $this->manifestPath = $this->basePath . '/bootstrap/cache/components.php';
    }

    /**
     * Get the current package manifest.
     *
     * @return array
     * @throws Exception
     */
    public function getManifest()
    {
        if (!is_null($this->manifest)) {
            return $this->manifest;
        }

        if (!file_exists($this->manifestPath)) {
            $this->build();
        }

        return $this->manifest = require $this->manifestPath;
    }

    /**
     * Get all of the sdk component class names.
     *
     * @return array
     * @throws Exception
     */
    public function components()
    {
        return $this->getManifest();
    }

    /**
     * Build the manifest and write it to disk.
     *
     * @throws Exception
     */
    public function build()
    {
        $this->manifest = $composer = [];

        $srcPath = $this->app()->getSrcPath();
        $namespace = $this->app()->getRootNamespace();

        foreach (Filesystem::scanFiles($srcPath) as $file) {
            $class = str_replace([$srcPath, '.php', '/'], [$namespace, '', '\\'], $file);

            if (!class_exists($class) || !is_subclass_of($class, Component::class)) {
                continue;
            }

            $reflect = new ReflectionClass($class);
            if ($reflect->isInstantiable() && $reflect->hasMethod('register')) {
                $this->manifest[] = $class;
            }
        }

        $this->write($this->manifest);
    }

    /**
     * Write the given manifest array to disk.
     *
     * @param  array  $manifest
     * @return void
     *
     * @throws \Exception
     */
    protected function write(array $manifest)
    {
        if (!is_writable(dirname($this->manifestPath))) {
            throw new Exception('The ' . dirname($this->manifestPath) . ' directory must be present and writable.');
        }

        file_put_contents($this->manifestPath, '<?php return ' . var_export($manifest, true) . ';');
    }
}
