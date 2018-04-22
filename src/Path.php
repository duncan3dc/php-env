<?php

namespace duncan3dc\Env;

use function is_dir;
use function realpath;
use function rtrim;
use function strlen;
use function substr;

class Path implements PathInterface
{
    /**
     * @var string $path The root path to use.
     */
    private $path;


    /**
     * Get an intsance for the apache document root.
     *
     * @return PathInterface
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
     * @return void
     */
    public function __construct(string $path)
    {
        # Remove any relative path elements
        $realroot = realpath($path);

        # Ensure the path is actually a directory
        if (!is_dir($realroot)) {
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
        return realpath($path);
    }


    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->path;
    }
}
