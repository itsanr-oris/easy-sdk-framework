<?php

namespace Foris\Demo\Sdk;



/**
 * Class Application
 */
class Application extends \Foris\Easy\Sdk\Application
{
    /**
     * Get artisan application instance.
     *
     * @return \Foris\Easy\Sdk\Console\Application
     */
    public function artisan()
    {
        $this->rebind('artisan', function ($app) {
            return new \Foris\Demo\Sdk\Console\Application($app);
        }, true);

        return parent::artisan();
    }
}
