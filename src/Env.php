<?php

namespace duncan3dc\Env;

use duncan3dc\Env\Variables\ProviderInterface;
use duncan3dc\Env\Variables\YamlProvider;

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
     * @var ProviderInterface $environment The underlying environment instance in use.
     */
    private static $environment;

    /**
     * @var string $hostname The name of the current domain running.
     */
    private static $hostname;

    /**
     * @var string $machine The name of the current server running.
     */
    private static $machine;

    /**
     * @var string $machine The current git version of the codebase running.
     */
    private static $revision;


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
     * @param int|string $use Either one of the PATH class constants or an actual path to a directory that exists, and is readable
     *
     * @return string
     */
    public static function path($append, $use = null)
    {
        $path = static::getPath();

        # If a different use has been requested then use it for this call only
        if ($use) {
            $previous = $path;
            static::usePath($use);
            $path = static::getPath();
            static::usePath($previous);
        }

        if (substr($append, 0, 1) != "/") {
            $path .= "/";
        }

        $path .= $append;

        return $path;
    }


    /**
     * Set the instance to use for environment variables/
     *
     * @param ProviderInterface $environment The instance to use.
     *
     * @return void
     */
    public static function setEnvironment(ProviderInterface $environment)
    {
        static::$environment = $environment;
    }


    /**
     * Get the instance to use for environment variables.
     *
     * @return ProviderInterface
     */
    public static function getEnvironment()
    {
        if (!static::$environment) {
            $path = static::path("data/env.yaml");
            static::setEnvironment(new YamlProvider($path));
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


    /**
     * Get an absolute path for the specified relative path, convert symlinks to a canonical path, and check the path exists.
     * This method is very similar to path() except the result is then run through php's standard realpath() function.
     *
     * @param string $append The relative path to append to the root path
     *
     * @return string
     */
    public static function realpath($append)
    {
        $path = static::path($append);
        return realpath($path);
    }


    /**
     * Get the current hostname from apache if this is mod_php otherwise the server's hostname.
     *
     * @return string
     */
    public static function getHostName()
    {
        if (static::$hostname === null) {
            # If the hostname is in the server array (usually set by apache) then use that
            if (!empty($_SERVER["HTTP_HOST"])) {
                static::$hostname = $_SERVER["HTTP_HOST"];

            # Otherwise use the hostname of this machine
            } else {
                static::$hostname = static::getMachineName();
            }
        }

        return static::$hostname;
    }


    /**
     * Get the current hostname of the machine.
     *
     * @return string
     */
    public static function getMachineName()
    {
        if (static::$machine === null) {
            static::$machine = php_uname("n");
        }

        return static::$machine;
    }


    /**
     * Get the revision number from the local git clone data.
     *
     * @param int $length The length of the revision hash to return
     *
     * @return string|void
     */
    public static function getRevision($length = 10)
    {
        if (static::$revision === null) {
            $revision = "";

            $path = static::path(".git");
            if (is_dir($path)) {
                $head = "{$path}/HEAD";
                if (file_exists($head)) {
                    $data = file_get_contents($head);
                    if (preg_match("/ref: ([^\s]+)\b/", $data, $matches)) {
                        $ref = $path . "/" . $matches[1];
                        if (file_exists($ref)) {
                            $revision = trim(file_get_contents($ref));
                        }
                    }
                }
            }

            static::$revision = $revision;
        }

        if ($length > 0 && strlen(static::$revision) > $length) {
            return substr(static::$revision, 0, $length);
        } else {
            return static::$revision;
        }
    }


    /**
     * Get the current useragent.
     *
     * @return string
     */
    public static function getUserAgent()
    {
        if (empty($_SERVER["USER_AGENT"])) {
            return "";
        }
        return $_SERVER["USER_AGENT"];
    }
}
