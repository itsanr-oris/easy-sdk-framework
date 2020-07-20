<?php

namespace Foris\Easy\Sdk\Console\Commands;

use Foris\Easy\Console\Commands\Command;
use Foris\Easy\Sdk\Console\Traits\HasSdkApplication;
use Foris\Easy\Sdk\Package\PackageManifest;

/**
 * Class PackageDiscoverCommand
 */
class PackageDiscoverCommand extends Command
{
    use HasSdkApplication;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'package:discover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild the cached package manifest';

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
        $manifest = $this->app()->get(PackageManifest::name());

        $manifest->build();

        foreach (array_keys($manifest->getManifest()) as $package) {
            $this->line("Discovered Package: <info>{$package}</info>");
        }

        $this->info('Package manifest generated successfully.');
    }
}
