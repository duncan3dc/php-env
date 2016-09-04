<?php

namespace duncan3dc\EnvTests;

use duncan3dc\Env\Env;
use duncan3dc\Env\Exception;

class EnvTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Env::usePath(__DIR__);
    }


    public function testUsePhpSelf()
    {
        $check = pathinfo($_SERVER["PHP_SELF"], \PATHINFO_DIRNAME);
        $check = realpath($check);

        Env::usePath(Env::PATH_PHP_SELF);
        $this->assertSame("{$check}/ok", Env::path("ok"));
    }
    public function testUsePhpSelfUnavailable()
    {
        unset($_SERVER["PHP_SELF"]);

        $this->setExpectedException(Exception::class, "PHP_SELF not defined");
        Env::usePath(Env::PATH_PHP_SELF);
    }


    public function testUseInvalidDirectory()
    {
        $this->setExpectedException(Exception::class, "Invalid path specified: /does-not-exist");
        Env::usePath("/does-not-exist");
    }


    public function testDocumentRoot1()
    {
        unset($_SERVER["DOCUMENT_ROOT"]);
        $this->setExpectedException(Exception::class, "DOCUMENT_ROOT not defined");
        Env::usePath(Env::PATH_DOCUMENT_ROOT);
    }

    public function testDocumentRoot2()
    {
        $_SERVER["DOCUMENT_ROOT"] = "/tmp";
        Env::usePath(Env::PATH_DOCUMENT_ROOT);
        $this->assertSame($_SERVER["DOCUMENT_ROOT"], Env::getPath());
    }

    public function testGetPath()
    {
        $this->assertSame(__DIR__, Env::getPath());
    }

    public function testPath1()
    {
        $this->assertSame(__DIR__ . "/", Env::path(""));
    }
    public function testPath2()
    {
        $this->assertSame(__DIR__ . "/test", Env::path("test"));
    }
    public function testPath3()
    {
        $this->assertSame(__DIR__ . "/test", Env::path("/test"));
    }

    public function testGetVar1()
    {
        $this->assertSame("OK", Env::getVar("test-string"));
    }
    public function testGetVar2()
    {
        $this->assertSame(7, Env::getVar("test-int"));
    }
    public function testGetVar3()
    {
        $this->assertSame(true, Env::getVar("test-bool"));
    }
    public function testGetVar4()
    {
        $this->assertNull(Env::getVar("does-not-exist"));
    }

    public function testRequireVar1()
    {
        $this->assertSame(null, Env::requireVar("test-exists"));
    }
    public function testRequireVar2()
    {
        $this->setExpectedException(Exception::class, "Failed to get the environment variable: does-not-exist");
        Env::requireVar("does-not-exist");
    }

    public function testSetVar1()
    {
        Env::setVar("test-new-var", "ok");
        $this->assertSame("ok", Env::getVar("test-new-var"));
    }
}
