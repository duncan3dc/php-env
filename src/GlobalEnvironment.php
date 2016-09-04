<?php

namespace duncan3dc\Env;

class GlobalEnvironment extends AbstractEnvironment
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
