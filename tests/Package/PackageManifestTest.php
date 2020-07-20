<?php

namespace Foris\Easy\Sdk\Tests\Package;

use Foris\Easy\Sdk\Package\PackageManifest;
use Foris\Easy\Sdk\Tests\TestCase;
use Foris\Easy\Support\Filesystem;

/**
 * Class PackageManifestTest
 */
class PackageManifestTest extends TestCase
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
    public function setUp(): void
    {
        parent::setUp();
        $this->manifestPath = $this->app()->getRootPath() . '/bootstrap/cache/packages.php';
    }

    /**
     * Get PackageManifest instance.
     *
     * @return PackageManifest
     * @throws \ReflectionException
     */
    protected function instance()
    {
        return new PackageManifest($this->app());
    }

    /**
     * Test build package manifest cache file.
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testBuildPackageManifestCache()
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
     * @param PackageManifest $instance
     * @throws \Exception
     * @depends testBuildPackageManifestCache
     */
    public function testGetServiceProvider(PackageManifest $instance)
    {
        $providers = [
            'Foris\\Demo\\Sdk\\Vendor\\Component\\ServiceProvider',
        ];

        $this->assertEquals($providers, $instance->providers());
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

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The '.dirname($this->manifestPath).' directory must be present and writable.');

        $this->instance()->build();
    }

    /**
     * Test get manifest while manifest cache file not exists
     *
     * @param PackageManifest $instance
     * @throws \ReflectionException
     * @throws \Exception
     * @depends testBuildPackageManifestCache
     */
    public function testGetManifestWhileManifestFileNotExists(PackageManifest $instance)
    {
        @unlink($this->manifestPath);
        $this->assertFileNotExists($this->manifestPath);

        $this->assertEquals($instance->getManifest(), $this->instance()->getManifest());
    }
}
