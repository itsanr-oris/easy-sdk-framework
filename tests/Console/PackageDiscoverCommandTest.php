<?php

namespace Foris\Easy\Sdk\Tests\Console;

use Foris\Easy\Sdk\Package\PackageManifest;
use Foris\Easy\Sdk\Tests\TestCase;

/**
 * Class PackageDiscoverCommandTest
 */
class PackageDiscoverCommandTest extends TestCase
{
    /**
     * Test package:discover
     *
     * @throws \Exception
     */
    public function testPackageDiscoverCommand()
    {
        $mock = \Mockery::mock(PackageManifest::class);
        $mock->shouldReceive('build')->andReturnTrue();

        $manifest = [
            'sdk-component-a' => [],
            'sdk-component-b' => [],
        ];
        $mock->shouldReceive('getManifest')->andReturn($manifest);

        $this->app()->rebind(PackageManifest::name(), function () use ($mock) {
            return $mock;
        });

        $this->call('package:discover');

        $this->assertStringContainsString('Discovered Package: sdk-component-a', $this->getDisplay());
        $this->assertStringContainsString('Discovered Package: sdk-component-b', $this->getDisplay());
    }
}
