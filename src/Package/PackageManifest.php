<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk\Package;

use Exception;
use Foris\Easy\Sdk\Application;
use Foris\Easy\Sdk\Component;

/**
 * Class PackageManifest
 */
class PackageManifest extends Component
{
    /**
     * The base path.
     *
     * @var string
     */
    public $basePath;

    /**
     * The vendor path.
     *
     * @var string
     */
    public $vendorPath;

    /**
     * The manifest path.
     *
     * @var string|null
     */
    public $manifestPath;

    /**
     * The loaded manifest array.
     *
     * @var array
     */
    public $manifest;

    /**
     * PackageManifest constructor.
     *
     * @param Application $app
     * @throws \ReflectionException
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->basePath = $app->getRootPath();
        $this->vendorPath = $this->basePath . '/vendor';
        $this->manifestPath = $this->basePath . '/bootstrap/cache/packages.php';
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
     * Get all of the service provider class names for all packages.
     *
     * @return array
     * @throws Exception
     */
    public function providers()
    {
        $providers = [];

        foreach ($this->getManifest() as $package => $configuration) {
            $providers = array_merge($providers, $configuration['providers']);
        }

        return $providers;
    }

    /**
     * Build the manifest and write it to disk.
     *
     * @return void
     * @throws Exception
     */
    public function build()
    {
        $this->manifest = $packages = [];

        if (file_exists($path = $this->vendorPath.'/composer/installed.json')) {
            $packages = json_decode(file_get_contents($path), true);
        }

        foreach ($packages as $package) {
            if (empty($package['extra']['easy-sdk'])) {
                continue;
            }

            $this->manifest[$this->format($package['name'])] = $package['extra']['easy-sdk'] ?? [];
        }

        $this->write($this->manifest);
    }

    /**
     * Format the given package name.
     *
     * @param  string  $package
     * @return string
     */
    protected function format($package)
    {
        return str_replace($this->vendorPath.'/', '', $package);
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
