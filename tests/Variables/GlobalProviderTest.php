<?php

namespace duncan3dc\EnvTests\Variables;

use duncan3dc\Env\Variables\GlobalProvider;
use PHPUnit\Framework\TestCase;

class GlobalProviderTest extends TestCase
{
    /** @var GlobalProvider */
    private $env;

    protected function setUp(): void
    {
        $_ENV["test-string"] = "OK";
        $_ENV["test-int"] = 7;
        $_ENV["test-bool"] = true;
        $_ENV["test-exists"] = null;
        $this->env = new GlobalProvider();
    }


    public function testGetString(): void
    {
        $this->assertSame("OK", $this->env->get("test-string"));
    }


    public function testGetInt(): void
    {
        $this->assertSame(7, $this->env->get("test-int"));
    }


    public function testGetBool(): void
    {
        $this->assertSame(true, $this->env->get("test-bool"));
    }


    public function testHas(): void
    {
        $this->assertTrue($this->env->has("test-exists"));
    }


    public function testHasnt(): void
    {
        $this->assertFalse($this->env->has("test-does-not-exist"));
    }


    public function testSetNew(): void
    {
        $this->env->set("test-new-var", "ok");
        $this->assertSame("ok", $this->env->get("test-new-var"));
    }


    public function testSetExisting(): void
    {
        $this->env->set("test-int", 4);
        $this->assertSame(4, $this->env->get("test-int"));
    }
}
