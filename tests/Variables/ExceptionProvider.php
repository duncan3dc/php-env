<?php

namespace duncan3dc\EnvTests\Variables;

use duncan3dc\Env\Variables\AbstractProvider;

class ExceptionProvider extends AbstractProvider
{
    protected function getVars(): array
    {
        throw new \Exception("No vars here");
    }
}
