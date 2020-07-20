<?php

namespace Foris\Easy\Sdk\Tests;

use Foris\Easy\Sdk\Console\Application;

/**
 * Class ApplicationTest
 */
class ApplicationTest extends TestCase
{
    /**
     * Gets the demo sdk application root path
     *
     * @return string
     */
    protected function rootPath()
    {
        // Demo application load from vfs://demo-sdk/vendor/../src/Application
        return $this->vfs()->url() . '/vendor/..';
    }

    /**
     * Test get sdk application root path
     *
     * @throws \ReflectionException
     */
    public function testGetRootPath()
    {
        // Demo application load from vfs://demo-sdk/vendor/../src/Application
        $this->assertEquals($this->rootPath(), $this->app()->getRootPath());
    }

    /**
     * Test set sdk application root path
     *
     * @throws \ReflectionException
     */
    public function testSetRootPath()
    {
        $rootPath = $this->app()->getRootPath();

        $this->app()->setRootPath('root_path');
        $this->assertEquals('root_path', $this->app()->getRootPath());

        $this->app()->setRootPath($rootPath);
    }

    /**
     * Test gets the file path
     *
     * @throws \ReflectionException
     */
    public function testGetPath()
    {
        $this->assertEquals($this->rootPath() . '/file.php', $this->app()->getPath('file.php'));
    }

    /**
     * Test gets the config file path.
     *
     * @throws \ReflectionException
     */
    public function testGetConfigFilePath()
    {
        $this->assertEquals($this->rootPath() . '/config/file.php', $this->app()->getConfigPath('file.php'));
    }

    /**
     * Test get src file path.
     *
     * @throws \ReflectionException
     */
    public function testGetSrcPath()
    {
        $this->assertEquals($this->rootPath() . '/src', $this->app()->getSrcPath());
    }

    /**
     * Test get sdk application root namespace
     */
    public function testGetRootNameSpace()
    {
        $this->assertEquals('Foris\Demo\Sdk', $this->app()->getRootNamespace());
        $this->assertEquals('Foris\Demo\Sdk', $this->app()->getRootNamespace());
    }

    /**
     * Test gets the artisan application instance.
     */
    public function testGetsArtisanApplication()
    {
        $this->assertInstanceOf(Application::class, $this->app()->artisan());
    }

    /**
     * Test publishes
     */
    public function testPublishes()
    {
        $expected = [
            'provider-a' => ['from-a' => 'to-a'],
            'provider-b' => ['from-b' => 'to-b'],
        ];
        $this->app()->publishes('provider-a', $expected['provider-a']);
        $this->app()->publishes('provider-b', $expected['provider-b']);

        $this->assertEquals($expected, $this->app()->getPublishes());
        $this->assertEquals($expected['provider-a'], $this->app()->getPublishes('provider-a'));
    }

    /**
     * Test get artisan commands
     */
    public function testCommands()
    {
        $this->app()->commands([
            'command-a', 'command-b',
        ]);

        $commands = $this->app()->getCommands();
        $this->assertTrue(in_array('command-a', $commands));
        $this->assertTrue(in_array('command-b', $commands));
    }
}
