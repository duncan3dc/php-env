<?php

namespace duncan3dc\EnvTests;

use PHPUnit\Framework\TestCase;

class AbstractEnvironmentTest extends TestCase
{
    private $env;

    public function setUp()
    {
        $this->env = new ExceptionEnvironment;
    }


    public function testHas()
    {
        $this->assertFalse($this->env->has("test-int"));
    }
}
