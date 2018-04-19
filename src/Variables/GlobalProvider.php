<?php

namespace duncan3dc\Env\Variables;

class GlobalProvider extends AbstractProvider
{
    /**
     * Get all defined environment variables.
     *
     * @return array
     */
    protected function getVars()
    {
        return $_ENV;
    }
}
