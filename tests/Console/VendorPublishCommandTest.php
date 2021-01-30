<?php

namespace Foris\Easy\Sdk\Tests\Console;

use Foris\Easy\Sdk\Tests\TestCase;
use Foris\Easy\Support\Filesystem;
use org\bovigo\vfs\vfsStream;

/**
 * Class VendorPublishCommandTest
 */
class VendorPublishCommandTest extends TestCase
{
    /**
     * Test publish file
     *
     * @throws \Exception
     */
    public function testPublishFile()
    {
        $src = $this->vfs()->url() . '/src.txt';
        $des = $this->vfs()->url() . '/des.txt';
        file_put_contents($src, 'test content');

        $this->assertFileExists($src);
        $this->assertFileNotExists($des);

        $this->app()->publishes('ServiceProvider', [$src => $des]);
        $this->call('vendor:publish', ['--provider' => 'ServiceProvider']);

        $this->assertFileExists($des);
        $this->assertFileEquals($src, $des);

        file_put_contents($des, 'new content');
        $this->assertFileNotEquals($src, $des);

        $this->call('vendor:publish', ['--provider' => 'ServiceProvider', '--force' => true]);
        $this->assertFileEquals($src, $des);
    }

    /**
     * Test publish directory
     *
     * @throws \Exception
     */
    public function testPublishDirectory()
    {
        $dir = vfsStream::newDirectory('src')->at($this->vfs());
        vfsStream::newFile('file.txt')->at($dir);

        $src = $this->vfs()->url() . '/src';
        $des = $this->vfs()->url() . '/des';

        $this->assertFileExists($src);
        $this->assertFileNotExists($des);

        $this->app()->publishes('ServiceProvider', [$src => $des]);
        $this->call('vendor:publish', ['--provider' => 'ServiceProvider']);

        $this->assertFileExists($des);

        foreach (Filesystem::scanFiles($src) as $file) {
            $this->assertFileEquals($file, str_replace($src, $des, $file));
        }
    }

    /**
     * Test publish not exists assets
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testPublishNotExistsAssets()
    {
        $this->app()->publishes('NotExistServiceProvider', [
            'not-exist-assets' => $this->app()->getConfigPath('not-exist-assets'),
        ]);

        $this->call('vendor:publish', ['--provider' => 'NotExistServiceProvider']);
        $this->assertHasSubString("Can't locate path: <not-exist-assets>", $this->getDisplay());
    }
}
