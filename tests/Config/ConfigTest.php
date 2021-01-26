<?php

namespace Foris\Easy\Sdk\Tests\Config;

use Foris\Easy\Sdk\Config\Config;
use Foris\Easy\Sdk\Tests\TestCase;

/**
 * Class ConfigTest
 */
class ConfigTest extends TestCase
{
    /**
     * Gets config instance
     *
     * @return Config
     */
    public function instance()
    {
        return new Config($this->app());
    }

    /**
     * Test custom configuration path.
     *
     * @return Config
     * @throws \ReflectionException
     */
    public function testCustomConfigPath()
    {
        $config = $this->instance();

        $this->assertEquals($this->app()->getRootPath() . '/config', $config->getConfigPath());

        $config->setConfigPath(__DIR__ . '/config');
        $this->assertEquals(__DIR__ . '/config', $config->getConfigPath());

        return $config;
    }

    /**
     * Test Config::has()
     *
     * @depends testCustomConfigPath
     * @param Config $config
     * @return Config
     */
    public function testHasConfiguration(Config $config)
    {
        $this->assertTrue($config->has('test-a.key-a'));
        $this->assertFalse($config->has('test-c.key-c'));

        return $config;
    }

    /**
     * Test Config::set()
     *
     * @depends testHasConfiguration
     * @param Config $config
     * @return Config
     */
    public function testDynamicSetConfiguration(Config $config)
    {
        $config->set('test-c.key-c', 'value-c');
        $this->assertTrue($config->has('test-c.key-c'));

        return $config;
    }

    /**
     * Test Config::get()
     * Test Config::all()
     *
     * @depends testDynamicSetConfiguration
     * @param Config $config
     */
    public function testGetConfigurationInfo(Config $config)
    {
        $this->assertEquals('value-c', $config->get('test-c.key-c'));
        $this->assertNull($config->get('not-exists-item'));
        $this->assertEquals('default', $config->get('not-exists-item', 'default'));

        $all = [
            'test-a' => ['key-a' => 'value-a'],

            'test-b' => ['key-b' => 'value-b'],

            'test-c' => ['key-c' => 'value-c'],
        ];
        $this->assertEquals($all, $config->all());
    }

    /**
     * Test array access
     *
     * @depends testDynamicSetConfiguration
     * @param Config $config
     */
    public function testArrayAccess(Config $config)
    {
        $this->assertFalse(isset($config['not-exists-item']));

        $config['not-exists-item'] = 'test-value';
        $this->assertTrue(isset($config['not-exists-item']));
        $this->assertEquals('test-value', $config['not-exists-item']);


        unset($config['not-exists-item']);
        $this->assertFalse(isset($config['not-exists-item']));
    }

    /**
     * Test override configuration
     *
     * @throws \ReflectionException
     */
    public function testOverrideConfiguration()
    {
        $instance = $this->instance();
        $instance->setConfigPath(__DIR__ . '/config');

        $all = [
            'test-a' => ['key-a' => 'value-a-override', 'key-b' => 'value-b'],

            'test-b' => ['key-b' => 'value-b'],
        ];

        $instance->mergeConfig([
            'test-a' => ['key-a' => 'value-a-override', 'key-b' => 'value-b']
        ]);

        $this->assertEquals($all, $instance->all());
    }
}
