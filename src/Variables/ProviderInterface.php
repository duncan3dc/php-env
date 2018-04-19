<?php

namespace duncan3dc\Env\Variables;

interface ProviderInterface
{

    /**
     * Check if a specific environment variable exists.
     *
     * @param string $var The name of the variable to check
     *
     * @return bool
     */
    public function has($var);


    /**
     * Get a specific environment variable.
     *
     * @param string $var The name of the variable to retrieve
     *
     * @return mixed
     */
    public function get($var);


    /**
     * Override an environment variable.
     *
     * @param string $var The name of the variable to set
     * @param string|int|boolean $value The value of the environment variable
     *
     * @return void
     */
    public function set($var, $value);
}
