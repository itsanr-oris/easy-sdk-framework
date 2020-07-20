<?php

namespace Foris\Easy\Sdk\Console\Commands;

use Foris\Easy\Console\Commands\Command;
use Foris\Easy\Sdk\Component\ComponentManifest;
use Foris\Easy\Sdk\Console\Traits\HasSdkApplication;

/**
 * Class ComponentDiscoverCommand
 */
class ComponentDiscoverCommand extends Command
{
    use HasSdkApplication;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'component:discover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the cached component manifest';

    /**
     * The console command help message.
     *
     * @var string
     */
    protected $help = '';

    /**
     * Execute the console command.
     */
    protected function handle()
    {
        $manifest = $this->app()->get(ComponentManifest::name());

        $manifest->build();

        foreach ($manifest->getManifest() as $component) {
            $this->line("Discovered Component: <info>{$component}</info>");
        }

        $this->info('Component manifest generated successfully.');
    }
}
