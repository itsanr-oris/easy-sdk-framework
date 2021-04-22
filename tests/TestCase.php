<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk\Tests;

use Foris\Demo\Sdk\Application;
use org\bovigo\vfs\vfsStream;

/**
 * Class TestCase
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    use ArtisanTestSuite;

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
    protected function setUp()
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
     * Assert a given string is a sub-string of another string.
     *
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertHasSubString($needle, $haystack, $message = '')
    {
        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString($needle, $haystack, $message);
            return ;
        }

        $this->assertTrue(mb_strpos($haystack, $needle) !== false);
    }

    /**
     * Assert a given string is a sub-string of another string.
     *
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertHasSubStringIgnoringCase($needle, $haystack, $message = '')
    {
        if (method_exists($this, 'assertStringContainsStringIgnoringCase')) {
            $this->assertStringContainsStringIgnoringCase($needle, $haystack, $message);
            return ;
        }

        $this->assertTrue(mb_stripos($haystack, $needle) !== false);
    }
}
