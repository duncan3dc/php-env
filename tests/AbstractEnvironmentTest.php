<?php

namespace duncan3dc\EnvTests;

class AbstractEnvironmentTest extends \PHPUnit_Framework_TestCase
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
