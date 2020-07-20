<?php

namespace Foris\Easy\Sdk\Tests;

use Foris\Easy\Sdk\Component;

/**
 * Class ComponentTest
 */
class ComponentTest extends TestCase
{
    /**
     * Test component register
     */
    public function testRegister()
    {
        Component::register($this->app());
        $this->assertInstanceOf(Component::class, $this->app()->get(Component::name()));
    }
}
