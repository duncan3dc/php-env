<?php

namespace duncan3dc\Env\Variables;

use duncan3dc\Serial\Yaml;

final class YamlProvider extends AbstractProvider
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
    public function __construct(string $path)
    {
        $this->path = $path;
    }


    protected function getVars(): array
    {
        return Yaml::decodeFromFile($this->path)->asArray();
    }
}
