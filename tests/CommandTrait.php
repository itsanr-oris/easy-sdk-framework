<?php

namespace Foris\Easy\Sdk\Tests;

use Foris\Easy\Console\Tests\InteractsWithIOCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\StreamOutput;

trait CommandTrait
{
    /**
     * ArrayInput instance.
     *
     * @var ArrayInput
     */
    protected $input;

    /**
     * ConsoleOutput instance.
     *
     * @var ConsoleOutput
     */
    protected $output;

    /**
     * Command instance
     *
     * @var InteractsWithIOCommand
     */
    protected $command;

    /**
     * Create input and output streams
     *
     * @param array $inputs
     * @return resource
     */
    protected function createStream(array $inputs)
    {
        $stream = fopen('php://memory', 'r+', false);

        foreach ($inputs as $input) {
            fwrite($stream, $input . \PHP_EOL);
        }

        rewind($stream);

        return $stream;
    }

    /**
     * Initializes the input property.
     *
     * @param array $inputs
     * @return ArrayInput
     */
    protected function initInput($inputs = [])
    {
        $this->input = new ArrayInput(['test-argument' => 'test-argument-value', '--test-option' => true]);
        $this->input->setStream($this->createStream($inputs));
        return $this->input;
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
     * Get InteractsWithIO mock instance
     *
     * @throws \Exception
     */
    protected function command()
    {
        if (empty($this->command)) {
            $this->command = new InteractsWithIOCommand();
            $this->command->run($this->initInput(), $this->initOutput());
        }

        return $this->command;
    }

    /**
     * Sets the user inputs.
     *
     * @param array $inputs
     * @return CommandTrait
     * @throws \Exception
     */
    protected function setInputs($inputs = [])
    {
        $this->command()->run($this->initInput($inputs), $this->initOutput());
        return $this;
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
}
