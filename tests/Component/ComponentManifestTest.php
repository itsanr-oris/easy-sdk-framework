<?php

namespace Foris\Easy\Sdk\Tests\Component;

use Foris\Easy\Sdk\Component\ComponentManifest;
use Foris\Easy\Sdk\Tests\TestCase;
use Foris\Easy\Support\Filesystem;

/**
 * Class ComponentManifestTest
 */
class ComponentManifestTest extends TestCase
{
    /**
     * The manifest path.
     *
     * @var string
     */
    protected $manifestPath = '';

    /**
     * Set up
     *
     * @throws \ReflectionException
     */
    public function setUp()
    {
        parent::setUp();
        $this->manifestPath = $this->app()->getRootPath() . '/bootstrap/cache/components.php';
    }

    /**
     * Get PackageManifest instance.
     *
     * @return ComponentManifest
     * @throws \ReflectionException
     */
    protected function instance()
    {
        return new ComponentManifest($this->app());
    }

    /**
     * Test build package manifest cache file.
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testBuildComponentManifestCache()
    {
        @unlink($this->manifestPath);
        $this->assertFileNotExists($this->manifestPath);

        $instance = $this->instance();
        $instance->build();
        $this->assertFileExists($this->manifestPath);

        return $instance;
    }

    /**
     * Test get service provider from package manifest.
     *
     * @param ComponentManifest $instance
     * @throws \Exception
     * @depends testBuildComponentManifestCache
     */
    public function testGetServiceProvider(ComponentManifest $instance)
    {
        $components = [
            'Foris\\Demo\\Sdk\\HelloWorld\\Hello',
        ];

        $this->assertEquals($components, $instance->components());
    }

    /**
     * Test manifest path not writable exception
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testManifestPathUnWritableException()
    {
        foreach (Filesystem::scanFiles(dirname($this->manifestPath)) as $file) {
            @unlink($file);
        }
        @rmdir(dirname($this->manifestPath));

        $class = \Exception::class;
        $message = 'The '.dirname($this->manifestPath).' directory must be present and writable.';
        $this->assertThrowException($class, $message);

        $this->instance()->build();
    }

    /**
     * Test get manifest while manifest cache file not exists
     *
     * @param ComponentManifest $instance
     * @throws \ReflectionException
     * @throws \Exception
     * @depends testBuildComponentManifestCache
     */
    public function testGetManifestWhileManifestFileNotExists(ComponentManifest $instance)
    {
        @unlink($this->manifestPath);
        $this->assertFileNotExists($this->manifestPath);

        $this->assertEquals($instance->getManifest(), $this->instance()->getManifest());
    }
}
