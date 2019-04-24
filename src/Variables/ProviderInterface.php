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
    public function has(string $var): bool;


    /**
     * Get a specific environment variable.
     *
     * @param string $var The name of the variable to retrieve
     *
     * @return string|int|bool
     */
    public function get(string $var);


    /**
     * Override an environment variable.
     *
     * @param string $var The name of the variable to set
     * @param string|int|bool $value The value of the environment variable
     *
     * @return void
     */
    public function set(string $var, $value): void;
}
