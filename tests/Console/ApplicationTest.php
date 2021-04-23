<?php

namespace Foris\Easy\Sdk\Tests\Console;

use Foris\Easy\Sdk\Tests\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\Test\TestLogger;

/**
 * Class ApplicationTest
 */
class ApplicationTest extends TestCase
{
    /**
     * Set up test environment
     */
    protected function setUp()
    {
        parent::setUp();

        $this->app()->rebind(LoggerInterface::class, function () {
            return new TestLogger();
        }, true);
    }

    /**
     * Gets the logger instance.
     *
     * @return TestLogger
     */
    protected function logger()
    {
        return $this->app()->get(LoggerInterface::class);
    }

    /**
     * Test gets the logger instance.
     */
    public function testGetLogger()
    {
        $this->assertInstanceOf(TestLogger::class, $this->logger());
    }

    /**
     * Test handle a exception while run an artisan command.
     *
     * @throws \Exception
     */
    public function testHandleException()
    {
        $this->command('test:exception');

        $this->assertCount(1, $this->logger()->records);
        $this->assertEquals('error', $this->logger()->records[0]['level']);
        $this->assertEquals('Test exception', $this->logger()->records[0]['message']);
    }
}
