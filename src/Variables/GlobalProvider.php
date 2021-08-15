<?php

namespace duncan3dc\Env\Variables;

final class GlobalProvider extends AbstractProvider
{


    protected function getVars(): array
    {
        return $_ENV;
    }
}
