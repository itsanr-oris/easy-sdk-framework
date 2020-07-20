<?php

namespace Foris\Easy\Sdk\Tests\Services;

/**
 * Class HelloService
 */
class HelloService
{
    /**
     * Return hello message.
     *
     * @return string
     */
    public function sayHello()
    {
        return 'Hello message from ' . static::class;
    }
}
