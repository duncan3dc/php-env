<?php

namespace duncan3dc\EnvTests;

use duncan3dc\Env\GlobalEnvironment;
use PHPUnit\Framework\TestCase;

class GlobalEnvironmentTest extends TestCase
{
    private $env;

    public function setUp()
    {
        $_ENV["test-string"] = "OK";
        $_ENV["test-int"] = 7;
        $_ENV["test-bool"] = true;
        $_ENV["test-exists"] = null;
        $this->env = new GlobalEnvironment;
    }


    public function testGetString()
    {
        $this->assertSame("OK", $this->env->get("test-string"));
    }


    public function testGetInt()
    {
        $this->assertSame(7, $this->env->get("test-int"));
    }


    public function testGetBool()
    {
        $this->assertSame(true, $this->env->get("test-bool"));
    }


    public function testHas()
    {
        $this->assertTrue($this->env->has("test-exists"));
    }


    public function testHasnt()
    {
        $this->assertFalse($this->env->has("test-does-not-exist"));
    }


    public function testSetNew()
    {
        $this->env->set("test-new-var", "ok");
        $this->assertSame("ok", $this->env->get("test-new-var"));
    }


    public function testSetExisting()
    {
        $this->env->set("test-int", 4);
        $this->assertSame(4, $this->env->get("test-int"));
    }
}
