<?php

namespace duncan3dc\Env\Variables;

class GlobalProvider extends AbstractProvider
{
    /**
     * Get all defined environment variables.
     *
     * @return array
     */
    protected function getVars(): array
    {
        return $_ENV;
    }
}
