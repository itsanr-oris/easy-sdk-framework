<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk\Tests;

use Foris\Demo\Sdk\Application;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Console\Tester\TesterTrait;

/**
 * Class TestCase
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    use TesterTrait {
        initOutput as traitInitOutput;
    }

    /**
     * Application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * vfsStreamDirectory instance
     *
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfs;

    /**
     * Set up test environment
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->initVfs();
        $this->app = new Application();
    }

    /**
     * Gets application instance
     *
     * @return Application
     */
    protected function app()
    {
        return $this->app;
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

    /**
     * Initializes the output property.
     *
     * @param array $options
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    protected function initOutput($options = [])
    {
        $this->traitInitOutput($options);
        return $this->getOutput();
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param       $command
     * @param array $parameters
     * @return int
     *
     * @throws \Exception
     */
    public function call($command, $parameters = [])
    {
        return $this->app()->artisan()->call($command, $parameters, $this->initOutput());
    }
}
