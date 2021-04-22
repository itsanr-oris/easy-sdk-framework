<?php /** @noinspection PhpIncludeInspection */

namespace Foris\Easy\Sdk\Tests\Console;

use Foris\Easy\Sdk\Tests\TestCase;
use Foris\Easy\Support\Arr;
use Foris\Easy\Support\Filesystem;

/**
 * Class MakeCommandTest
 */
class MakeCommandTest extends TestCase
{
    /**
     * Get expected generate file content
     *
     * @param        $class
     * @param array  $options
     * @return mixed|string
     * @throws \Foris\Easy\Support\Exceptions\FileNotFountException
     */
    protected function getExpectedFileContent($class, $options = [])
    {
        if (empty($class)) {
            return '';
        }

        $namespace = 'Foris\Demo\Sdk\Console\Commands';

        if (strrpos($class, '/') !== false) {
            $segments = explode('/', $class);
            $class = end($segments);

            $namespaceSegments = Arr::except($segments, [count($segments) - 1]);
            $namespace = $namespace . '\\' . implode('\\', $namespaceSegments);
        }

        $type = isset($options['type']) ? $options['type'] : '';
        $file = $type == 'generate-command' ? 'DummyGenerateCommand.stub' : 'DummyCommand.stub';
        $stub = Filesystem::get( __DIR__ . '/../../src/Console/Stubs/' . $file);

        return str_replace(
            ['DummyNamespace', 'DummyClass', 'dummy:command'], [$namespace, $class, 'command:name'], $stub
        );
    }

    /**
     * Test make:command
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testMakeCommand()
    {
        $command = $this->app()->getRootPath() . '/src/Console/Commands/CustomCommand.php';

        $this->assertFileNotExists($command);
        $this->command('make:command', ['name' => 'CustomCommand']);
        $this->assertFileExists($command);
        $this->assertEquals($this->getExpectedFileContent('CustomCommand'), Filesystem::get($command));
    }

    /**
     * Test make:command
     *
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testMakeGenerateCommand()
    {
        $command = $this->app()->getRootPath() . '/src/Console/Commands/CustomGenerateCommand.php';

        $this->assertFileNotExists($command);
        $this->command('make:command', ['name' => 'CustomGenerateCommand', '--type' => 'generate-command']);
        $this->assertFileExists($command);
        $this->assertEquals($this->getExpectedFileContent('CustomGenerateCommand', ['type' => 'generate-command']), Filesystem::get($command));
    }
}
