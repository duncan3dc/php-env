<?php

namespace duncan3dc\EnvTests\Variables;

use PHPUnit\Framework\TestCase;

class AbstractProviderTest extends TestCase
{
    /** @var ExceptionProvider */
    private $env;

    protected function setUp(): void
    {
        $this->env = new ExceptionProvider();
    }


    public function testHas(): void
    {
        $this->assertFalse($this->env->has("test-int"));
    }
}
