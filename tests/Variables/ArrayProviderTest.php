<?php

namespace duncan3dc\EnvTests\Variables;

use duncan3dc\Env\Variables\ArrayProvider;
use PHPUnit\Framework\TestCase;

class ArrayProviderTest extends TestCase
{
    /**
     * @var ArrayProvider The instance we are testing
     */
    private $env;

    public function setUp(): void
    {
        $this->env = new ArrayProvider([
            "test-string" => "here",
            "test-int" => 8,
            "test-bool" => false,
            "test-exists" => null,
        ]);
    }


    public function testGetString()
    {
        $this->assertSame("here", $this->env->get("test-string"));
    }


    public function testGetInt()
    {
        $this->assertSame(8, $this->env->get("test-int"));
    }


    public function testGetBool()
    {
        $this->assertSame(false, $this->env->get("test-bool"));
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
