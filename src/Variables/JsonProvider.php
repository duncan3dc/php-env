<?php

namespace duncan3dc\Env\Variables;

use duncan3dc\Serial\Json;

final class JsonProvider extends AbstractProvider
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
        return Json::decodeFromFile($this->path)->asArray();
    }
}
