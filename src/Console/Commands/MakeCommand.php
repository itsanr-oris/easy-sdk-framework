<?php

namespace Foris\Easy\Sdk\Console\Commands;

/**
 * Class MakeCommand
 */
class MakeCommand extends \Foris\Easy\Console\Commands\MakeCommand
{
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('type') == 'generate-command') {
            return __DIR__ . '/../Stubs/DummyGenerateCommand.stub';
        }

        return __DIR__ . '/../Stubs/DummyCommand.stub';
    }
}
