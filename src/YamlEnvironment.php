<?php

namespace duncan3dc\Env;

use duncan3dc\Serial\Yaml;

class YamlEnvironment extends AbstractEnvironment
{
    /**
     * @var string $path The path to the file.
     */
    private $path;


    /**
     * Create a new instance.
     *
     * @param string $path The location of the file
     */
    public function __construct($path)
    {
        $this->path = $path;
    }


    /**
     * Get all defined environment variables.
     *
     * @return array
     */
    protected function getVars()
    {
        return Yaml::decodeFromFile($this->path)->asArray();
    }
}
