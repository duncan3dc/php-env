<?php

namespace duncan3dc\Env;

interface EnvironmentInterface
{
    /**
     * Check if an environment variable exists
     *
     * @param string $key The name of the variable to look for
     *
     * @return bool
     */
    public function has(string $key): bool;


    /**
     * Get a specific environment variable, or null if it doesn't exist.
     *
     * @param string $key The name of the variable to retrieve
     *
     * @return mixed
     */
    public function get(string $key);


    /**
     * Get a specific environment variable, throw an exception if it doesn't exist.
     *
     * @param string $key The name of the variable to retrieve
     *
     * @return mixed
     */
    public function require(string $key);


    /**
     * Override an environment variable.
     *
     * @param string $key The name of the variable to set
     * @param string|int|boolean $value The value of the environment variable
     *
     * @return void
     */
    public function set(string $key, $value);


    /**
     * Get an absolute path for the specified relative path (relative to the currently used internal root path).
     *
     * @param string $apend The relative path to append to the root path
     *
     * @return string
     */
    public function path(string $append): string;


    /**
     * Get an absolute path for the specified relative path, convert symlinks to a canonical path, and check the path exists.
     * This method is very similar to path() except the result is then run through php's standard realpath() function.
     *
     * @param string $append The relative path to append to the root path
     *
     * @return string
     */
    public function realpath(string $append): string;


    /**
     * Get the current hostname from apache if available, otherwise get the server's hostname.
     *
     * @return string
     */
    public function getHostName(): string;


    /**
     * Get the current hostname of the machine.
     *
     * @return string
     */
    public function getMachineName(): string;


    /**
     * Get the revision number from the local git clone data.
     *
     * @param int $length The length of the revision hash to return
     *
     * @return string
     */
    public function getRevision(int $length = 10): string;


    /**
     * Get the current user agent.
     *
     * @return string
     */
    public function getUserAgent(): string;
}
