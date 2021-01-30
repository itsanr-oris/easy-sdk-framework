<?php /** @noinspection PhpMethodParametersCountMismatchInspection */

namespace Foris\Easy\Sdk\Tests\Console;

use Foris\Easy\Sdk\Component\ComponentManifest;
use Foris\Easy\Sdk\Tests\TestCase;

/**
 * Class ComponentDiscoverCommandTest
 */
class ComponentDiscoverCommandTest extends TestCase
{
    /**
     * Test component:discover
     *
     * @throws \Exception
     */
    public function testComponentDiscoverCommand()
    {
        $mock = \Mockery::mock(ComponentManifest::class);
        $mock->shouldReceive('build')->andReturn(true);

        $manifest = [
            'sdk-component-a',
            'sdk-component-b',
        ];
        $mock->shouldReceive('getManifest')->andReturn($manifest);

        $this->app()->rebind(ComponentManifest::name(), function () use ($mock) {
            return $mock;
        });

        $this->call('component:discover');

        $this->assertHasSubString('Discovered Component: sdk-component-a', $this->getDisplay());
        $this->assertHasSubString('Discovered Component: sdk-component-b', $this->getDisplay());
    }
}
