<?php

namespace Foris\Easy\Sdk\Console\Traits;

use Foris\Easy\Sdk\Console\Application;

/**
 * Class HasSdkApplication
 */
trait HasSdkApplication
{
    /**
     * Gets the application instance.
     *
     * @return Application
     */
    abstract public function getApplication();

    /**
     * Gets the sdk application instance.
     *
     * @return \Foris\Easy\Sdk\Application
     */
    public function app()
    {
        return $this->getApplication()->app();
    }
}
