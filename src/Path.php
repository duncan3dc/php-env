<?php

namespace duncan3dc\Env;

use function is_dir;
use function is_string;
use function realpath;
use function rtrim;
use function strlen;
use function substr;

final class Path implements PathInterface
{
    /**
     * @var string $path The root path to use.
     */
    private $path;


    /**
     * Get an instance for the apache document root.
     *
     * @return PathInterface
     * @throws Exception
     */
    public static function webroot(): PathInterface
    {
        # Use the document root normally set via apache
        if (empty($_SERVER["DOCUMENT_ROOT"])) {
            throw new Exception("DOCUMENT_ROOT not defined");
        }

        return new self($_SERVER["DOCUMENT_ROOT"]);
    }


    /**
     * Get an instance for the parent of the vendor directory (commonly the project root)
     *
     * @return PathInterface
     * @throws Exception
     */
    public static function root(): PathInterface
    {
        return new self(__DIR__ . "/../../../..");
    }


    /**
     * Create a new instance.
     *
     * @param string $path The local filesystem path to represent
     *
     * @throws Exception
     */
    public function __construct(string $path)
    {
        # Remove any relative path elements
        $realroot = realpath($path);

        # Ensure the path is actually a directory
        if ($realroot === false || !is_dir($realroot)) {
            throw new Exception("Invalid path specified: {$path}");
        }

        $this->path = $realroot;
    }


    /**
     * @inheritDoc
     */
    public function path(string $append): string
    {
        $path = $this->path;

        $append = rtrim($append, "/");

        if (strlen($append) > 0 && substr($append, 0, 1) !== "/") {
            $path .= "/";
        }

        $path .= $append;

        return $path;
    }


    /**
     * @inheritDoc
     */
    public function realpath(string $append): string
    {
        $path = $this->path($append);

        $realpath = realpath($path);
        if ($realpath === false) {
            throw new Exception("Invalid path specified: {$path}");
        }

        return $realpath;
    }


    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->path;
    }
}
