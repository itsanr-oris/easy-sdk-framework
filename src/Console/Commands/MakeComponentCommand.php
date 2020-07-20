<?php /** @noinspection PhpUndefinedClassInspection */

namespace Foris\Easy\Sdk\Console\Commands;

use Foris\Easy\Console\Commands\GenerateCommand;
use Foris\Easy\Sdk\Console\Traits\HasSdkApplication;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class MakeComponent
 */
class MakeComponentCommand extends GenerateCommand
{
    use HasSdkApplication;

    /**
     * Command name
     *
     * @var string
     */
    protected $name = 'make:component';

    /**
     * Command description
     *
     * @var string
     */
    protected $description = 'Create a new sdk component';

    /**
     * Help message
     *
     * @var string
     */
    protected $help = '';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'component';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub() : string
    {
        if ($this->option('alias')) {
            return __DIR__ . '/../Stubs/DummyComponentWithAliasName.stub';
        }

        return __DIR__ . '/../Stubs/DummyComponent.stub';
    }

    /**
     * Execute the console command.
     *
     * @return bool
     * @throws \Foris\Easy\Support\Exceptions\FileNotFountException
     * @throws \ReflectionException
     * @throws \Exception
     */
    protected function handle()
    {
        if ($result = parent::handle()) {
            system('php artisan component:discover --ansi');
        }

        return $result;
    }

    /**
     * Replace the alias name for the given stub.
     *
     * @param $stub
     * @return mixed
     */
    protected function replaceAliasName($stub)
    {
        return str_replace('DummyAliasName', $this->option('alias'), $stub);
    }

    /**
     * Build the class with the given name.
     *
     * @param $name
     * @return mixed
     * @throws \Foris\Easy\Support\Exceptions\FileNotFountException
     */
    public function buildClass($name)
    {
        return $this->replaceAliasName(parent::buildClass($name));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['alias', 'a', InputOption::VALUE_OPTIONAL, 'Set an alias name for current component.'],
        ]);
    }
}
