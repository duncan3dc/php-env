<?php

namespace duncan3dc\EnvTests;

use duncan3dc\Env\Env;
use duncan3dc\Env\Exception;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    public function setUp()
    {
        # Clear the cached values from previous tests
        $class = new \ReflectionClass(Env::class);
        foreach ($class->getProperties() as $property) {
            $property->setAccessible(true);
            $property->setValue(null);
        }

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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("PHP_SELF not defined");
        Env::usePath(Env::PATH_PHP_SELF);
    }


    public function testUseInvalidDirectory()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid path specified: /does-not-exist");
        Env::usePath("/does-not-exist");
    }


    public function testDocumentRoot1()
    {
        unset($_SERVER["DOCUMENT_ROOT"]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("DOCUMENT_ROOT not defined");
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

    public function testPathWithUse()
    {
        $this->assertSame("/tmp/directory", Env::path("directory", "/tmp"));
    }

    public function testRealpath1()
    {
        $this->assertSame(__DIR__, Env::realpath(""));
    }
    public function testRealpath2()
    {
        $this->assertSame(__DIR__, Env::realpath("."));
    }
    public function testRealpath3()
    {
        $this->assertSame(realpath(__DIR__ . "/.."), Env::realpath(".."));
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
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Failed to get the environment variable: does-not-exist");
        Env::requireVar("does-not-exist");
    }

    public function testSetVar1()
    {
        Env::setVar("test-new-var", "ok");
        $this->assertSame("ok", Env::getVar("test-new-var"));
    }


    public function testGetHostNameApache()
    {
        $_SERVER["HTTP_HOST"] = "example.com";
        $this->assertSame("example.com", Env::getHostName());
    }
    public function testGetHostNameDefault()
    {
        unset($_SERVER["HTTP_HOST"]);
        $this->assertSame(php_uname("n"), Env::getHostName());
    }


    public function testGetMachineName()
    {
        $this->assertSame(php_uname("n"), Env::getMachineName());
    }


    private function setRevision()
    {
        Env::usePath(__DIR__ . "/data");
        $path = Env::path(".git");
        if (!is_dir($path)) {
            mkdir($path);
        }
        file_put_contents("{$path}/HEAD", "ref: master");
        file_put_contents("{$path}/master", "abcdefghijk");
    }
    public function testRevision1()
    {
        $this->setRevision();
        $this->assertSame("abcdefghij", Env::getRevision());
    }
    public function testRevision2()
    {
        $this->setRevision();
        $this->assertSame("abcdefghijk", Env::getRevision(0));
    }
    public function testRevision3()
    {
        $this->setRevision();
        $this->assertSame("abcde", Env::getRevision(5));
    }

    public function testUserAgent()
    {
        $_SERVER["USER_AGENT"] = "special-browser";
        $this->assertSame("special-browser", Env::getUserAgent());
    }
    public function testUserAgentFail()
    {
        unset($_SERVER["USER_AGENT"]);
        $this->assertSame("", Env::getUserAgent());
    }
}
