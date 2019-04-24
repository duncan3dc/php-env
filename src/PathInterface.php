<?php

namespace duncan3dc\Env;

interface PathInterface
{
    /**
     * Get an absolute path for the specified relative path (relative to the currently used internal root path).
     *
     * @param string $append The relative path to append to the root path
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
     * Convert the path to a string.
     *
     * @return string
     */
    public function __toString(): string;
}
