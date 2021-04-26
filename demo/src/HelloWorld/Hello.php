<?php

namespace Foris\Demo\Sdk\HelloWorld;

use Foris\Easy\Sdk\Component;

/**
 * Class Hello
 */
class Hello extends Component
{
    /**
     * Return a hello message.
     *
     * @return string
     */
    public function hello()
    {
        return 'Hello easy sdk.';
    }

    /**
     * Gets sdk configuration.
     *
     * @param        $key
     * @param string $default
     * @return mixed
     */
    public function getSdkConfig($key, $default = '')
    {
        return $this->getConfig($key, $default);
    }
}
