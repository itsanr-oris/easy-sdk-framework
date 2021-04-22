<?php

namespace Foris\Easy\Sdk\Tests;

use Foris\Demo\Sdk\Console\Application as Artisan;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * Trait ArtisanTestSuite
 */
trait ArtisanTestSuite
{
    /**
     * ConsoleOutput instance.
     *
     * @var \Symfony\Component\Console\Output\ConsoleOutput
     */
    protected $output;

    /**
     * Console application instance.
     *
     * @return \Foris\Easy\Sdk\Application
     */
    abstract protected function app();

    /**
     * Gets the artisan command application instance.
     *
     * @return Artisan
     * @throws \ReflectionException
     */
    protected function artisan()
    {
        return new Artisan($this->app());
    }

    /**
     * Sets the input interactivity.
     *
     * @param bool $interactive If the input should be interactive
     * @return $this
     * @throws \ReflectionException
     */
    protected function setInteractive($interactive = true)
    {
        $this->artisan()->setInteractive($interactive);
        return $this;
    }

    /**
     * Sets the interactive inputs.
     *
     * @param array $inputs
     * @return $this
     * @throws \ReflectionException
     */
    protected function setInputs($inputs = [])
    {
        $this->artisan()->setInputs($inputs);
        return $this;
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param $command
     * @param $params
     * @return int
     * @throws \Exception
     */
    protected function command($command, $params = [])
    {
        return $this->artisan()->call($command, $params, $this->initOutput());
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
    protected function getDisplay($normalize = false)
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
}
