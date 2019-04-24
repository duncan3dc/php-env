<?php

namespace duncan3dc\Env;

use duncan3dc\Env\Variables\ProviderInterface;
use function file_exists;
use function file_get_contents;
use function is_dir;
use function php_uname;
use function preg_match;
use function strlen;
use function substr;
use function trim;

class Environment implements EnvironmentInterface
{
    /**
     * @var ProviderInterface $provider The environment variable provider.
     */
    private $provider;

    /**
     * @var PathInterface $root The root path to use.
     */
    private $root;


    /**
     * Create a new instance.
     *
     * @param ProviderInterface $provider
     * @param PathInterface $root The root path to use for any path calculations
     * @throws Exception
     */
    public function __construct(ProviderInterface $provider, PathInterface $root = null)
    {
        $this->provider = $provider;

        if ($root === null) {
            $root = Path::root();
        }
        $this->root = $root;
    }


    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->provider->has($key);
    }


    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if (!$this->provider->has($key)) {
            return null;
        }

        return $this->provider->get($key);
    }


    /**
     * @inheritDoc
     */
    public function require(string $key)
    {
        if (!$this->provider->has($key)) {
            throw new Exception("Failed to get the environment variable: {$key}");
        }

        return $this->provider->get($key);
    }


    /**
     * @inheritDoc
     */
    public function set(string $key, $value): void
    {
        $this->provider->set($key, $value);
    }


    /**
     * @inheritDoc
     */
    public function path(string $append): string
    {
        return $this->root->path($append);
    }


    /**
     * @inheritDoc
     */
    public function realpath(string $append): string
    {
        return $this->root->realpath($append);
    }


    /**
     * @inheritDoc
     */
    public function getHostName(): string
    {
        if ($this->has("hostname")) {
            return $this->get("hostname");
        }

        # If the hostname is in the server array (usually set by apache) then use that
        if (!empty($_SERVER["HTTP_HOST"])) {
            $this->set("hostname", $_SERVER["HTTP_HOST"]);

        # Otherwise use the hostname of this machine
        } else {
            $this->set("hostname", $this->getMachineName());
        }

        return $this->get("hostname");
    }


    /**
     * @inheritDoc
     */
    public function getMachineName(): string
    {
        if (!$this->has("machine")) {
            $this->set("machine", php_uname("n"));
        }

        return $this->get("machine");
    }


    /**
     * @inheritDoc
     */
    public function getRevision(int $length = 10): string
    {
        if (!$this->has("revision")) {
            $revision = "";

            $path = $this->path(".git");
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

            $this->set("revision", $revision);
        }

        $revision = $this->get("revision");

        if ($length > 0 && strlen($revision) > $length) {
            $revision = substr($revision, 0, $length);
        }

        return $revision;
    }


    /**
     * @inheritDoc
     */
    public function getUserAgent(): string
    {
        if (!$this->has("user-agent")) {
            $this->set("user-agent", $_SERVER["USER_AGENT"] ?? "");
        }

        return $this->get("user-agent");
    }
}
