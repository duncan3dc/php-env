<?php

namespace duncan3dc\EnvTests\Variables;

use duncan3dc\Env\Variables\ArrayProvider;
use PHPUnit\Framework\TestCase;

class ArrayProviderTest extends TestCase
{
    /** @var ArrayProvider */
    private $env;

    protected function setUp(): void
    {
        $this->env = new ArrayProvider([
            "test-string" => "here",
            "test-int" => 8,
            "test-bool" => false,
            "test-exists" => null,
        ]);
    }


    public function testGetString(): void
    {
        $this->assertSame("here", $this->env->get("test-string"));
    }


    public function testGetInt(): void
    {
        $this->assertSame(8, $this->env->get("test-int"));
    }


    public function testGetBool(): void
    {
        $this->assertSame(false, $this->env->get("test-bool"));
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
