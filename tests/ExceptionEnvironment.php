<?php

namespace duncan3dc\EnvTests;

use duncan3dc\Env\AbstractEnvironment;

class ExceptionEnvironment extends AbstractEnvironment
{
    protected function getVars()
    {
        throw new \Exception("No vars here");
    }
}
