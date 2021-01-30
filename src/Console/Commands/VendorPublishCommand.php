<?php /** @noinspection PhpInconsistentReturnPointsInspection */

namespace Foris\Easy\Sdk\Console\Commands;

use Foris\Easy\Console\Commands\Command;
use Foris\Easy\Sdk\Console\Traits\HasSdkApplication;
use Foris\Easy\Support\Filesystem;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class VendorPublishCommand
 */
class VendorPublishCommand extends Command
{
    use HasSdkApplication;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'vendor:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish any publishable assets from vendor packages';

    /**
     * The console command help message.
     *
     * @var string
     */
    protected $help = '';

    /**
     * Execute the console command.
     *
     * @throws \ReflectionException
     */
    protected function handle()
    {
        $this->publish($this->option('provider'));

        $this->info('Publishing complete.');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['provider', 'p', InputOption::VALUE_REQUIRED, 'The service provider that has assets you want to publish'],

            ['force', 'f', InputOption::VALUE_OPTIONAL, 'Overwrite any existing files']
        ]);
    }

    /**
     * Publishes the assets.
     *
     * @param string $provider
     * @throws \ReflectionException
     */
    public function publish($provider)
    {
        foreach ($this->app()->getPublishes($provider) as $from => $to) {
            $this->publishItem($from, $to);
        }
    }

    /**
     * Publish the given item from and to the given location.
     *
     * @param $from
     * @param $to
     * @throws \ReflectionException
     */
    protected function publishItem($from, $to)
    {
        if (Filesystem::isFile($from)) {
            return $this->publishFile($from, $to);
        }

        if (Filesystem::isDirectory($from)) {
            return $this->publishDirectory($from, $to);
        }

        $this->error("Can't locate path: <{$from}>");
    }

    /**
     * Publish the file to the given path.
     *
     * @param      $from
     * @param      $to
     * @param bool $output
     * @throws \ReflectionException
     */
    protected function publishFile($from, $to, $output = true)
    {
        if (!Filesystem::exists($to) || $this->option('force')) {
            $this->createParentDirectory(dirname($to));

            copy($from, $to);

            if ($output) {
                $this->status($from, $to, 'File');
            }
        }
    }

    /**
     * Publish the directory to the given directory.
     *
     * @param $from
     * @param $to
     * @throws \ReflectionException
     */
    protected function publishDirectory($from, $to)
    {
        $files = Filesystem::scanFiles($from);

        foreach ($files as $file) {
            $this->publishFile($file, str_replace($from, $to, $file), false);
        }

        $this->status($from, $to, 'Directory');
    }

    /**
     * Create the directory to house the published files if needed.
     *
     * @param  string  $directory
     * @return void
     */
    protected function createParentDirectory($directory)
    {
        if (!Filesystem::isDirectory($directory)) {
            Filesystem::makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Write a status message to the console.
     *
     * @param  string $from
     * @param  string $to
     * @param  string $type
     * @return void
     * @throws \ReflectionException
     */
    protected function status($from, $to, $type)
    {
        $from = str_replace($this->app()->getRootPath(), '', realpath($from));

        $to = str_replace($this->app()->getRootPath(), '', realpath($to));

        $this->line('<info>Copied '.$type.'</info> <comment>['.$from.']</comment> <info>To</info> <comment>['.$to.']</comment>');
    }
}
