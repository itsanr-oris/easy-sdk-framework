<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk\Tests;

use Foris\Demo\Sdk\Application;
use org\bovigo\vfs\vfsStream;

/**
 * Class TestCase
 */
class TestCase extends \Foris\Easy\Sdk\Test\TestCase
{
    /**
     * vfsStreamDirectory instance
     *
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfs;

    /**
     * Set up test environment
     */
    protected function setUp()
    {
        $this->initVfs();
        parent::setUp();
    }

    /**
     * Create sdk application instance.
     *
     * @return Application|\Foris\Easy\Sdk\Application
     */
    protected function createApplication()
    {
        return new Application();
    }

    /**
     * Create artisan command application instance.
     *
     * @return \Foris\Demo\Sdk\Console\Application|\Foris\Easy\Sdk\Console\Application
     * @throws \ReflectionException
     */
    protected function createArtisan()
    {
        return new \Foris\Demo\Sdk\Console\Application($this->app());
    }

    /**
     * Get vfs instance
     *
     * @return \org\bovigo\vfs\vfsStreamDirectory
     */
    protected function vfs()
    {
        return $this->vfs;
    }

    /**
     * Init vfs instance
     *
     * @return \org\bovigo\vfs\vfsStreamDirectory
     */
    protected function initVfs()
    {
        if (empty($this->vfs)) {
            $base = vfsStream::setup('demo-sdk');
            $this->vfs = vfsStream::copyFromFileSystem(__DIR__ . '/../demo', $base);
            require_once $this->vfs->url() . '/vendor/autoload.php';
        }

        return $this->vfs;
    }
}
