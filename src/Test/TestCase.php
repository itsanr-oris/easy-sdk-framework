<?php

namespace Foris\Easy\Sdk\Test;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * Class TestCase
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Application instance.
     *
     * @var \Foris\Easy\Sdk\Application
     */
    protected $app;

    /**
     * Artisan command application instance.
     *
     * @var \Foris\Easy\Sdk\Console\Application
     */
    protected $artisan;

    /**
     * ConsoleOutput instance.
     *
     * @var ConsoleOutput
     */
    protected $output;

    /**
     * Set up the test environment
     */
    protected function setUp()
    {
        parent::setUp();
        $this->app = $this->createApplication();
        $this->artisan = $this->createArtisan();
    }

    /**
     * Tear down the test environment.
     */
    protected function tearDown()
    {
        $this->clearTestApplicationInstance();
        parent::tearDown();
    }

    /**
     * Clear test application instance.
     *
     * @return $this
     */
    protected function clearTestApplicationInstance()
    {
        $this->app = null;
        $this->artisan = null;
        return $this;
    }

    /**
     * Create sdk application instance.
     *
     * @return \Foris\Easy\Sdk\Application
     */
    abstract protected function createApplication();

    /**
     * Create artisan command application instance.
     *
     * @return \Foris\Easy\Sdk\Console\Application
     */
    abstract protected function createArtisan();

    /**
     * Gets the application instance
     *
     * @return \Foris\Easy\Sdk\Application
     */
    protected function app()
    {
        return $this->app;
    }

    /**
     * Gets the artisan command application instance.
     *
     * @return \Foris\Easy\Sdk\Console\Application
     */
    protected function artisan()
    {
        return $this->artisan;
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param       $command
     * @param array $parameters
     * @return int
     *
     * @throws \Exception
     */
    public function command($command, $parameters = [])
    {
        return $this->artisan()->call($command, $parameters, $this->initOutput());
    }

    /**
     * Initializes the output property.
     *
     * @param array $options
     * @return \Symfony\Component\Console\Output\OutputInterface
     */
    protected function initOutput($options = [])
    {
        $this->output = new StreamOutput(fopen('php://memory', 'w', false));
        if (isset($options['decorated'])) {
            $this->output->setDecorated($options['decorated']);
        }
        if (isset($options['verbosity'])) {
            $this->output->setVerbosity($options['verbosity']);
        }
        return $this->output;
    }

    /**
     * Gets the display returned by the last execution of the command or application.
     *
     * @param bool $normalize
     * @return string The display
     */
    public function getDisplay($normalize = false)
    {
        if (null === $this->output) {
            throw new \RuntimeException('Output not initialized, did you execute the command before requesting the display?');
        }

        rewind($this->output->getStream());

        $display = stream_get_contents($this->output->getStream());

        if ($normalize) {
            $display = str_replace(\PHP_EOL, "\n", $display);
        }

        return $display;
    }

    /**
     * Assert a given string is a sub-string of another string.
     *
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertHasSubString($needle, $haystack, $message = '')
    {
        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString($needle, $haystack, $message);
            return ;
        }

        $this->assertTrue(mb_strpos($haystack, $needle) !== false);
    }

    /**
     * Assert a given string is a sub-string of another string.
     *
     * @param string $needle
     * @param string $haystack
     * @param string $message
     */
    protected function assertHasSubStringIgnoringCase($needle, $haystack, $message = '')
    {
        if (method_exists($this, 'assertStringContainsStringIgnoringCase')) {
            $this->assertStringContainsStringIgnoringCase($needle, $haystack, $message);
            return ;
        }

        $this->assertTrue(mb_stripos($haystack, $needle) !== false);
    }
}
