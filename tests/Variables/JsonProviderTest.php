<?php

namespace duncan3dc\EnvTests\Variables;

use duncan3dc\Env\Variables\JsonProvider;
use PHPUnit\Framework\TestCase;

class JsonProviderTest extends TestCase
{
    /** @var JsonProvider */
    private $env;

    protected function setUp(): void
    {
        $this->env = new JsonProvider(__DIR__ . "/../data/env.json");
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
        $this->assertFalse($this->env->has("does-not-exist"));
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
