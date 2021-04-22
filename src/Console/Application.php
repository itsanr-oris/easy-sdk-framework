<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Foris\Easy\Sdk\Console;

use Foris\Easy\Sdk\Application as SdkApplication;

/**
 * Class Application
 */
class Application extends \Foris\Easy\Console\Application
{
    /**
     * Sdk application instance.
     *
     * @var SdkApplication
     */
    protected $app;

    /**
     * Application constructor.
     *
     * @param SdkApplication $app
     * @throws \ReflectionException
     */
    public function __construct(SdkApplication $app)
    {
        $this->app = $app;
        parent::__construct($app->getRootPath(), 'Easy sdk console', SdkApplication::VERSION);
    }

    /**
     * Gets the sdk application instance.
     *
     * @return SdkApplication
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * Register the commands for the application.
     */
    protected function commands()
    {
        $this->registerSdkCommands();
    }

    /**
     * Register sdk artisan commands.
     */
    protected function registerSdkCommands()
    {
        foreach ($this->app()->getCommands() as $command) {
            $this->add($this->app()->get($command));
        }
    }
}
