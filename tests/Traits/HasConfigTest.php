<?php

namespace Foris\Easy\Sdk\Tests\Traits;

use Foris\Demo\Sdk\HelloWorld\Hello;
use Foris\Easy\Sdk\Tests\TestCase;

/**
 * Class HasConfigTest
 */
class HasConfigTest extends TestCase
{
    /**
     * Test get configuration.
     */
    public function testGetConfig()
    {
        $this->assertEquals(['sub-key' => 'sub-value'], $this->app()->get(Hello::name())->getSdkConfig('test-config.key'));
    }
}
