<?php

namespace duncan3dc\EnvTests;

use duncan3dc\Env\Exception;
use duncan3dc\Env\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /** @var Path $path The instance we are testing */
    private $path;


    protected function setUp(): void
    {
        $this->path = new Path("/tmp");
    }


    public function testWebroot1(): void
    {
        $_SERVER["DOCUMENT_ROOT"] = "/tmp/";
        $path = Path::webroot();
        $this->assertSame("/tmp", (string) $path);
    }
    public function testWebroot2(): void
    {
        unset($_SERVER["DOCUMENT_ROOT"]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("DOCUMENT_ROOT not defined");
        Path::webroot();
    }
    public function testWebroot3(): void
    {
        $_SERVER["DOCUMENT_ROOT"] = "";
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("DOCUMENT_ROOT not defined");
        Path::webroot();
    }


    public function testRoot1(): void
    {
        $expected = realpath(__DIR__ . "/../../../..");
        $path = Path::root();
        $this->assertSame($expected, (string) $path);
    }


    public function testConstructor1(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid path specified: /does/not/exist");
        new Path("/does/not/exist");
    }
    public function testConstructor2(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid path specified: " . __FILE__);
        new Path(__FILE__);
    }


    public function testPath1(): void
    {
        $this->assertSame("/tmp", $this->path->path(""));
    }
    public function testPath2(): void
    {
        $this->assertSame("/tmp/test", $this->path->path("test"));
    }
    public function testPath3(): void
    {
        $this->assertSame("/tmp/test", $this->path->path("/test/"));
    }


    public function testRealpath1(): void
    {
        $this->assertSame("/tmp", $this->path->realpath(""));
    }
    public function testRealpath2(): void
    {
        $this->assertSame("/tmp", $this->path->realpath("."));
    }
    public function testRealpath3(): void
    {
        $this->assertSame("/", $this->path->realpath(".."));
    }


    public function testToString(): void
    {
        $this->assertSame("/tmp", (string) $this->path);
    }
}
