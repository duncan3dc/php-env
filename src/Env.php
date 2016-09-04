<?php

namespace duncan3dc\Env;

class Env
{
    /**
     * For use with usePath() - Represents the apache document root
     */
    const PATH_DOCUMENT_ROOT = 701;

    /**
     * For use with usePath() - Represents the directory that the PHP_SELF filename is in
     */
    const PATH_PHP_SELF = 702;

    /**
     * For use with usePath() - Represents the parent of the vendor directory (commonly the project root)
     */
    const PATH_VENDOR_PARENT = 703;

    /**
     * @var string $path The root path to use.
     */
    private static $path;

    /**
     * @var EnvironmentInterface $environment The underlying environment instance in use.
     */
    private static $environment;

    /**
     * Set the root path to use in the path methods.
     *
     * @param int|string $argument Either one of the PATH class constants or an actual path to a directory that exists, and is readable
     *
     * @return void
     */
    public static function usePath($argument)
    {
        # Use the document root normally set via apache
        if ($argument === self::PATH_DOCUMENT_ROOT) {
            if (empty($_SERVER["DOCUMENT_ROOT"])) {
                throw new Exception("DOCUMENT_ROOT not defined");
            }
            $argument = $_SERVER["DOCUMENT_ROOT"];
        }

        # Get the full path of the running script and use its directory
        if ($argument === self::PATH_PHP_SELF) {
            if (empty($_SERVER["PHP_SELF"])) {
                throw new Exception("PHP_SELF not defined");
            }
            $argument = pathinfo($_SERVER["PHP_SELF"], \PATHINFO_DIRNAME);
        }

        # Calculate the parent of the vendor directory and use that
        if ($argument === self::PATH_VENDOR_PARENT) {
            $argument = __DIR__ . "/../../../..";
        }

        # Remove any relative path elements
        $path = realpath($argument);

        # Ensure the path is actually a diectory
        if (!is_dir($path)) {
            throw new Exception("Invalid path specified: {$argument}");
        }

        static::$path = $path;
    }


    /**
     * Get the root path, by default this is the parent directory of the composer vender directory.
     *
     * @return string
     */
    public static function getPath()
    {
        if (!static::$path) {
            static::usePath(self::PATH_VENDOR_PARENT);
        }

        if (!static::$path) {
            throw new Exception("Failed to establish the current environment path");
        }

        return static::$path;
    }


    /**
     * Get an absolute path for the specified relative path (relative to the currently used internal root path).
     *
     * @param string $apend The relative path to append to the root path
     *
     * @return string
     */
    public static function path($append)
    {
        $path = static::getPath();

        if (substr($append, 0, 1) != "/") {
            $path .= "/";
        }

        $path .= $append;

        return $path;
    }


    /**
     * Set the instance to use for environment variables/
     *
     * @param EnvironmentInterface $environment The instance to use.
     *
     * @return void
     */
    public static function setEnvironment(EnvironmentInterface $environment)
    {
        static::$environment = $environment;
    }


    /**
     * Get the instance to use for environment variables.
     *
     * @return EnvironmentInterface
     */
    public static function getEnvironment()
    {
        if (!static::$environment) {
            $path = static::path("data/env.yaml");
            static::setEnvironment(new YamlEnvironment($path));
        }

        return static::$environment;
    }


    /**
     * Get a specific environment variable, or null if it doesn't exist.
     *
     * @param string $var The name of the variable to retrieve
     *
     * @return mixed
     */
    public static function getVar($var)
    {
        $environment = static::getEnvironment();

        if (!$environment->has($var)) {
            return null;
        }

        return $environment->get($var);
    }


    /**
     * Get a specific environment variable, throw an exception if it doesn't exist.
     *
     * @param string $var The name of the variable to retrieve
     *
     * @return mixed
     */
    public static function requireVar($var)
    {
        $environment = static::getEnvironment();

        if (!$environment->has($var)) {
            throw new Exception("Failed to get the environment variable: {$var}");
        }

        return $environment->get($var);
    }


    /**
     * Override an environment variable.
     *
     * @param string $var The name of the variable to set
     * @param string|int|boolean $value The value of the environment variable
     *
     * @return void
     */
    public static function setVar($var, $value)
    {
        static::getEnvironment()->set($var, $value);
    }
}
