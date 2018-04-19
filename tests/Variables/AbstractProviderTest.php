<?php

namespace duncan3dc\EnvTests\Variables;

use duncan3dc\EnvTests\Variables\ExceptionProvider;
use PHPUnit\Framework\TestCase;

class AbstractProviderTest extends TestCase
{
    private $env;

    public function setUp()
    {
        $this->env = new ExceptionProvider;
    }


    public function testHas()
    {
        $this->assertFalse($this->env->has("test-int"));
    }
}
