<?php

namespace Foris\Easy\Sdk;

use Foris\Easy\Support\Str;

/**
 * Class Component
 */
class Component
{
    /**
     * Application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Component constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->bootstrap();
    }

    /**
     * Get Application instance.
     *
     * @return Application
     */
    public function app() : Application
    {
        return $this->app;
    }

    /**
     * Bootstrap the sdk component.
     */
    protected function bootstrap()
    {
    }

    /**
     * Register component
     *
     * @param Application $app
     */
    public static function register(Application $app)
    {
        $app->singleton(static::name(), function () use ($app) {
            return new static($app);
        });
    }

    /**
     * Get component name
     *
     * @return     string  component name
     */
    public static function name()
    {
        $classSegment = explode('\\', static::class);

        $nameSegment = [];
        foreach ($classSegment as $segment) {
            $nameSegment[] = Str::snake($segment);
        }

        return strtolower(implode('.', $nameSegment));
    }
}
