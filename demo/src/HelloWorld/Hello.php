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
}
