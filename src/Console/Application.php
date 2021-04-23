<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

namespace Foris\Easy\Sdk\Console;

use Foris\Easy\Sdk\Application as SdkApplication;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

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
     * Bootstrap the console application.
     *
     * @param $rootPath
     */
    protected function bootstrap($rootPath)
    {
        parent::bootstrap($rootPath);

        $this->setAutoExit(false);
        $this->setCatchExceptions(false);
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

    /**
     * Runs the current application.
     *
     * @param InputInterface|null  $input
     * @param OutputInterface|null $output
     * @return int
     * @codeCoverageIgnore
     * @throws \Exception
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $input = $input ?: new ArgvInput;
        $output = $output ?: new ConsoleOutput;

        try {
            return parent::run($input, $output);
        } catch (\Exception $exception) {
            $this->reportException($exception);
            $this->handleException($exception, $output);
            return 1;
        } catch (\Throwable $exception) {
            $this->reportException($exception);
            $this->handleException($exception, $output);
            return 1;
        }
    }

    /**
     * Reports a caught exception.
     *
     * @param \Throwable $exception
     */
    public function reportException($exception)
    {
        if ($this->app()->has(LoggerInterface::class)) {
            $this->app()->get(LoggerInterface::class)->error($exception->getMessage(), ['exception' => $exception]);
        }
    }

    /**
     * Handle a caught exception.
     *
     * @param \Exception      $exception
     * @param OutputInterface $output
     * @throws \Exception
     * @codeCoverageIgnore
     */
    public function handleException($exception, $output)
    {
        if (method_exists($this, 'renderException')) {
            $this->renderException($exception, $output);
            return ;
        }

        if (method_exists($this, 'renderThrowable')) {
            $this->renderThrowable($exception, $output);
            return ;
        }

        throw $exception;
    }
}
