<?php

namespace Foris\Easy\Sdk\Console\Commands;

/**
 * Class MakeCommand
 */
class MakeCommand extends \Foris\Easy\Console\Commands\MakeCommand
{
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Console\Commands';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('type') == 'generate-command') {
            return __DIR__ . '/../Stubs/DummyGenerateCommand.stub';
        }

        return __DIR__ . '/../Stubs/DummyCommand.stub';
    }
}
