<?php

namespace duncan3dc\Env\Variables;

final class ArrayProvider extends AbstractProvider
{
    /**
     * @var array<string,string|int|bool|null> The variables.
     */
    private $data = [];

    /**
     * Create a new instance.
     *
     * @param array<string,string|int|bool|null> $data The environment variables
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }


    protected function getVars(): array
    {
        return $this->data;
    }
}
