<?php

namespace Foris\Easy\Sdk\Tests;

use Foris\Easy\Sdk\Component;
use Foris\Easy\Sdk\Tests\Services\ServiceProvider;

/**
 * Class ServiceProviderTest
 */
class ServiceProviderTest extends TestCase
{
    /**
     * ServiceProvider instance.
     *
     * @var ServiceProvider
     */
    protected $provider;

    /**
     * Set up test environment
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->app()->get('config')->set('test-config', ['key-a' => 'test-value']);
    }

    /**
     * Gets the service provider instance.
     *
     * @return ServiceProvider
     */
    protected function provider()
    {
        if (empty($this->provider)) {
            $this->provider = new ServiceProvider($this->app());
        }

        return $this->provider;
    }

    /**
     * Test register commands
     */
    public function testRegisterCommands()
    {
        $provider = $this->provider();
        $provider->register();

        foreach ($provider->getCommands() as $command) {
            $this->assertTrue(in_array($command, $this->app()->getCommands()));
        }
    }

    /**
     * Test register publishable asserts.
     */
    public function testRegisterPublishableAssets()
    {
        $provider = $this->provider();
        $provider->register();
        $this->assertEquals($provider->getPublishableAssets(), $this->app()->getPublishes(ServiceProvider::class));
    }

    /**
     * Test register components.
     */
    public function testRegisterComponents()
    {
        $provider = $this->provider();
        $provider->register();

        foreach ($provider->getComponents() as $component) {
            if (class_exists($component) && is_subclass_of($component, Component::class)) {
                $this->assertInstanceOf($component, $this->app()->get(call_user_func([$component, 'name'])));
            }
        }
    }

    /**
     * Test merge configuration.
     */
    public function testMergeConfiguration()
    {
        $config = [
            'key-a' => 'test-value'
        ];
        $this->assertEquals($config, $this->app()->get('config')->get('test-config', []));

        $provider = $this->provider();
        $provider->register();

        $expected = array_merge($config, require __DIR__ . '/Services/config/config.php');
        $this->assertEquals($expected, $this->app()->get('config')->get('test-config', []));
    }
}
