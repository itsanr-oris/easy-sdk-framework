<?php /** @noinspection ALL */

namespace Foris\Easy\Sdk\Tests;

use Foris\Easy\Sdk\ServiceContainer;
use Foris\Easy\Sdk\Tests\Services\HelloService;
use Foris\Easy\Sdk\Tests\Services\ReplaceService;
use Foris\Easy\Sdk\Tests\Services\ServiceProvider;

/**
 * Class ServiceContainerTest
 */
class ServiceContainerTest extends TestCase
{
    /**
     * Test bind service
     */
    public function testBindService()
    {
        $container = $this->app();

        $name = 'helloService';
        $container->bind($name, function () {
            return new HelloService();
        });

        $service = $container->get($name);
        $this->assertInstanceOf(HelloService::class, $service);
        $this->assertEquals($service, $container->get($name));
        $this->assertNotSame($service, $container->get($name));
    }

    /**
     * Test bind singleton service
     */
    public function testBindSingletonService()
    {
        $container = $this->app();

        $name = 'helloService';
        $container->singleton($name, function () {
            return new HelloService();
        });

        $service = $container->get($name);
        $this->assertInstanceOf(HelloService::class, $service);
        $this->assertSame($service, $container->get($name));

        return $container;
    }

    /**
     * Test rebind service
     *
     * @param ServiceContainer $container
     * @depends testBindSingletonService
     */
    public function testRebindService(ServiceContainer $container)
    {
        $container->rebind('helloService', function () {
            return new ReplaceService();
        });

        $replaceService = $container->get('helloService');
        $this->assertInstanceOf(ReplaceService::class, $replaceService);
    }

    /**
     * Test register service provider
     */
    public function testRegisterServiceProvider()
    {
        $provider = [
            ServiceProvider::class,
            'NotExistProvider',
        ];

        $instance = $this->app();
        $instance->registerProviders($provider);

        $this->assertTrue(isset($instance['helloService']));
    }

    /**
     * Test array access
     *
     * @depends testBindSingletonService
     * @param ServiceContainer $instance
     */
    public function testMagicAccess(ServiceContainer $instance)
    {
        $instance->testService = 'test service';
        $this->assertEquals('test service', $instance->get('testService'));
        $this->assertEquals($instance->get('helloService'), $instance->helloService);
    }

    /**
     * Test checks if a parameter or an object is set.
     *
     * @param ServiceContainer $instance
     * @depends testBindSingletonService
     */
    public function testHasService(ServiceContainer $instance)
    {
        $this->assertFalse($instance->has('no_exist_service'));
        $this->assertTrue($instance->has('helloService'));
    }
}
