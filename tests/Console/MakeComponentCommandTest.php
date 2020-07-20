<?php

namespace Foris\Easy\Sdk\Tests\Console;

use Foris\Easy\Sdk\Tests\TestCase;
use Foris\Easy\Support\Arr;
use Foris\Easy\Support\Filesystem;

/**
 * Class MakeComponentCommandTest
 */
class MakeComponentCommandTest extends TestCase
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

        $namespace = 'Foris\Demo\Sdk';

        if (strrpos($class, '/') !== false) {
            $segments = explode('/', $class);
            $class = end($segments);

            $namespaceSegments = Arr::except($segments, [count($segments) - 1]);
            $namespace = $namespace . '\\' . implode('\\', $namespaceSegments);
        }

        $alias = $options['alias'] ?? '';
        $file = !empty($alias) ? 'DummyComponentWithAliasName.stub' : 'DummyComponent.stub';
        $stub = Filesystem::get( __DIR__ . '/../../src/Console/Stubs/' . $file);

        return str_replace(
            ['DummyNamespace', 'DummyClass', 'DummyAliasName'], [$namespace, $class, $alias], $stub
        );
    }

    /**
     * Test make sdk component
     *
     * @throws \ReflectionException
     * @throws \Foris\Easy\Support\Exceptions\FileNotFountException
     * @throws \Exception
     */
    public function testMakeSdkComponent()
    {
        $path = $this->app()->getRootPath() . '/src/Component/Demo.php';
        $this->assertFileNotExists($path);

        $this->call('make:component', ['name' => 'Component/Demo']);

        $this->assertFileExists($path);
        $this->assertEquals($this->getExpectedFileContent('Component/Demo'), Filesystem::get($path));

        $this->call('make:component', ['name' => 'Component/Demo', '--alias' => 'demo', '--force' => true]);
        $this->assertFileExists($path);
        $this->assertEquals($this->getExpectedFileContent('Component/Demo', ['alias' => 'demo']), Filesystem::get($path));
    }
}
