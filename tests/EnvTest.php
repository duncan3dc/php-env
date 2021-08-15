<?php

namespace duncan3dc\EnvTests;

use duncan3dc\Env\Env;
use duncan3dc\Env\Exception;
use PHPUnit\Framework\TestCase;

class EnvTest extends TestCase
{
    protected function setUp(): void
    {
        # Clear the cached values from previous tests
        $class = new \ReflectionClass(Env::class);
        foreach ($class->getProperties() as $property) {
            $property->setAccessible(true);
            $property->setValue(null);
        }

        Env::usePath(__DIR__);
    }


    public function testUseVendorParent(): void
    {
        if (!getenv("TRAVIS")) {
            $this->markTestSkipped("Test can only be run when the location of source code is known");
        }

        Env::usePath(Env::PATH_VENDOR_PARENT);
        $this->assertSame("/home/travis", Env::getPath());
    }


    public function testUsePhpSelf(): void
    {
        $check = pathinfo($_SERVER["PHP_SELF"], \PATHINFO_DIRNAME);
        $check = realpath($check);

        Env::usePath(Env::PATH_PHP_SELF);
        $this->assertSame("{$check}/ok", Env::path("ok"));
    }
    public function testUsePhpSelfUnavailable(): void
    {
        unset($_SERVER["PHP_SELF"]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("PHP_SELF not defined");
        Env::usePath(Env::PATH_PHP_SELF);
    }


    public function testUseInvalidDirectory(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Invalid path specified: /does-not-exist");
        Env::usePath("/does-not-exist");
    }


    public function testDocumentRoot1(): void
    {
        unset($_SERVER["DOCUMENT_ROOT"]);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("DOCUMENT_ROOT not defined");
        Env::usePath(Env::PATH_DOCUMENT_ROOT);
    }

    public function testDocumentRoot2(): void
    {
        $_SERVER["DOCUMENT_ROOT"] = "/tmp";
        Env::usePath(Env::PATH_DOCUMENT_ROOT);
        $this->assertSame($_SERVER["DOCUMENT_ROOT"], Env::getPath());
    }

    public function testGetPath(): void
    {
        $this->assertSame(__DIR__, Env::getPath());
    }


    /**
     * Ensure that we use data/env.yaml by default.
     */
    public function testGetEnvironment1(): void
    {
        Env::usePath(__DIR__);
        $environment = Env::getEnvironment();

        $this->assertSame("yaml", $environment->get("file-type"));
    }


    /**
     * Ensure that we use global environment if data/env.yaml does not exist.
     */
    public function testGetEnvironment2(): void
    {
        $_ENV["file-type"] = "global";

        Env::usePath(__DIR__ . "/data");
        $environment = Env::getEnvironment();

        $this->assertSame("global", $environment->get("file-type"));
    }


    public function testPath1(): void
    {
        $this->assertSame(__DIR__ . "/", Env::path(""));
    }
    public function testPath2(): void
    {
        $this->assertSame(__DIR__ . "/test", Env::path("test"));
    }
    public function testPath3(): void
    {
        $this->assertSame(__DIR__ . "/test", Env::path("/test"));
    }

    public function testPathWithUse(): void
    {
        $this->assertSame("/tmp/directory", Env::path("directory", "/tmp"));
    }

    public function testRealpath1(): void
    {
        $this->assertSame(__DIR__, Env::realpath(""));
    }
    public function testRealpath2(): void
    {
        $this->assertSame(__DIR__, Env::realpath("."));
    }
    public function testRealpath3(): void
    {
        $this->assertSame(realpath(__DIR__ . "/.."), Env::realpath(".."));
    }

    public function testGetVar1(): void
    {
        $this->assertSame("OK", Env::getVar("test-string"));
    }
    public function testGetVar2(): void
    {
        $this->assertSame(7, Env::getVar("test-int"));
    }
    public function testGetVar3(): void
    {
        $this->assertSame(true, Env::getVar("test-bool"));
    }
    public function testGetVar4(): void
    {
        $this->assertNull(Env::getVar("does-not-exist"));
    }

    public function testRequireVar1(): void
    {
        $this->assertSame(null, Env::requireVar("test-exists"));
    }
    public function testRequireVar2(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Failed to get the environment variable: does-not-exist");
        Env::requireVar("does-not-exist");
    }

    public function testSetVar1(): void
    {
        Env::setVar("test-new-var", "ok");
        $this->assertSame("ok", Env::getVar("test-new-var"));
    }


    public function testGetHostNameApache(): void
    {
        $_SERVER["HTTP_HOST"] = "example.com";
        $this->assertSame("example.com", Env::getHostName());
    }
    public function testGetHostNameDefault(): void
    {
        unset($_SERVER["HTTP_HOST"]);
        $this->assertSame(php_uname("n"), Env::getHostName());
    }


    public function testGetMachineName(): void
    {
        $this->assertSame(php_uname("n"), Env::getMachineName());
    }


    private function setRevision(): void
    {
        Env::usePath(__DIR__ . "/data");
        $path = Env::path(".git");
        if (!is_dir($path)) {
            mkdir($path);
        }
        file_put_contents("{$path}/HEAD", "ref: master");
        file_put_contents("{$path}/master", "abcdefghijk");
    }
    public function testRevision1(): void
    {
        $this->setRevision();
        $this->assertSame("abcdefghij", Env::getRevision());
    }
    public function testRevision2(): void
    {
        $this->setRevision();
        $this->assertSame("abcdefghijk", Env::getRevision(0));
    }
    public function testRevision3(): void
    {
        $this->setRevision();
        $this->assertSame("abcde", Env::getRevision(5));
    }

    public function testUserAgent(): void
    {
        $_SERVER["USER_AGENT"] = "special-browser";
        $this->assertSame("special-browser", Env::getUserAgent());
    }
    public function testUserAgentFail(): void
    {
        unset($_SERVER["USER_AGENT"]);
        $this->assertSame("", Env::getUserAgent());
    }
}
