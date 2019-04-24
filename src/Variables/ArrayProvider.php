<?php

namespace duncan3dc\Env\Variables;

final class ArrayProvider extends AbstractProvider
{
    /**
     * @var array The variables.
     */
    private $data = [];

    /**
     * Create a new instance.
     *
     * @param array $data The environment variables
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }


    /**
     * Get all defined environment variables.
     *
     * @return array
     */
    protected function getVars(): array
    {
        return $this->data;
    }
}
