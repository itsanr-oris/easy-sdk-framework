<?php

namespace Foris\Demo\Sdk\Console\Commands;

use Foris\Easy\Console\Commands\Command;

/**
 * Class ExceptionCommand
 */
class ExceptionCommand extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected $name = 'test:exception';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    protected function handle()
    {
        throw new \Exception('Test exception');
    }
}
