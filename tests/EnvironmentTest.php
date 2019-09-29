<?php

namespace duncan3dc\EnvTests;

use duncan3dc\Env\Environment;
use duncan3dc\Env\EnvironmentInterface;
use duncan3dc\Env\Exception;
use duncan3dc\Env\Path;
use duncan3dc\Env\PathInterface;
use duncan3dc\Env\Variables\GlobalProvider;
use duncan3dc\Env\Variables\ProviderInterface;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

use function file_put_contents;
use function is_dir;
use function mkdir;
use function php_uname;

class EnvironmentTest extends TestCase
{
    /** @var Environment $environment The instance we are testing */
    private $environment;

    /** @var ProviderInterface&MockInterface $provider A provide instance to test with */
    private $provider;

    /** @var PathInterface&MockInterface $path A path instance to test with */
    private $path;


    public function setUp(): void
    {
        $this->provider = Mockery::mock(ProviderInterface::class);
        $this->path = Mockery::mock(PathInterface::class);
        $this->environment = new Environment($this->provider, $this->path);
    }


    public function tearDown(): void
    {
        Mockery::close();
    }


    public function testConstructor()
    {
        $environment = new Environment($this->provider);
        $expected = Path::root()->path("extra");
        $result = $environment->path("extra");
        $this->assertSame($expected, $result);
    }


    public function testHas1()
    {
        $this->provider->shouldReceive("has")->once()->with("elephant")->andReturn(true);
        $result = $this->environment->has("elephant");
        $this->assertSame(true, $result);
    }
    public function testHas2()
    {
        $this->provider->shouldReceive("has")->once()->with("disillusioned")->andReturn(false);
        $result = $this->environment->has("disillusioned");
        $this->assertSame(false, $result);
    }


    public function testGet1()
    {
        $this->provider->shouldReceive("has")->once()->with("contrarian")->andReturn(true);
        $this->provider->shouldReceive("get")->once()->with("contrarian")->andReturn("03");
        $result = $this->environment->get("contrarian");
        $this->assertSame("03", $result);
    }
    public function testGet2()
    {
        $this->provider->shouldReceive("has")->once()->with("doomed")->andReturn(false);
        $result = $this->environment->get("doomed");
        $this->assertSame(null, $result);
    }


    public function testRequire1()
    {
        $this->provider->shouldReceive("has")->once()->with("so-long-fish")->andReturn(true);
        $this->provider->shouldReceive("get")->once()->with("so-long-fish")->andReturn("05");
        $result = $this->environment->require("so-long-fish");
        $this->assertSame("05", $result);
    }
    public function testRequire2()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Failed to get the environment variable: so-long-fish");
        $this->provider->shouldReceive("has")->once()->with("so-long-fish")->andReturn(false);
        $this->environment->require("so-long-fish");
    }


    public function testSet1()
    {
        $this->provider->shouldReceive("set")->once()->with("talk", "walk")->andReturn("dsgldujhfus");
        $this->environment->set("talk", "walk");
        $this->assertTrue(true);
    }


    public function testPath1()
    {
        $this->path->shouldReceive("path")->once()->with("by/down")->andReturn("the/river");
        $result = $this->environment->path("by/down");
        $this->assertSame("the/river", $result);
    }


    public function testRealpath1()
    {
        $this->path->shouldReceive("realpath")->once()->with("delicious")->andReturn("DLB");
        $result = $this->environment->realpath("delicious");
        $this->assertSame("DLB", $result);
    }


    private function getGlobalEnvironment(): EnvironmentInterface
    {
        return new Environment(new GlobalProvider(), $this->path);
    }


    public function testGetHostNameApache()
    {
        $environment = $this->getGlobalEnvironment();
        $_SERVER["HTTP_HOST"] = "example.com";
        $this->assertSame("example.com", $environment->getHostName());
    }
    public function testGetHostNameDefault()
    {
        $environment = $this->getGlobalEnvironment();
        unset($_SERVER["HTTP_HOST"]);
        $this->assertSame(php_uname("n"), $environment->getHostName());
    }
    public function testGetHostNameCache()
    {
        $environment = $this->getGlobalEnvironment();
        $environment->set("hostname", "hourglass");
        $this->assertSame("hourglass", $environment->getHostName());
    }


    public function testGetMachineName1()
    {
        $environment = $this->getGlobalEnvironment();
        $this->assertSame(php_uname("n"), $environment->getMachineName());
    }
    public function testGetMachineName2()
    {
        $environment = $this->getGlobalEnvironment();
        $environment->set("machine", "feathers");
        $this->assertSame("feathers", $environment->getMachineName());
    }


    private function withRevision(): EnvironmentInterface
    {
        $environment = new Environment(new GlobalProvider(), new Path(__DIR__ . "/data"));
        $path = $environment->path(".git");
        if (!is_dir($path)) {
            mkdir($path);
        }
        file_put_contents("{$path}/HEAD", "ref: master");
        file_put_contents("{$path}/master", "abcdefghijk");
        return $environment;
    }
    public function testRevision1()
    {
        $environment = $this->withRevision();
        $this->assertSame("abcdefghij", $environment->getRevision());
    }
    public function testRevision2()
    {
        $environment = $this->withRevision();
        $this->assertSame("abcdefghijk", $environment->getRevision(0));
    }
    public function testRevision3()
    {
        $environment = $this->withRevision();
        $this->assertSame("abcde", $environment->getRevision(5));
    }


    public function testUserAgent()
    {
        $environment = $this->getGlobalEnvironment();
        $_SERVER["USER_AGENT"] = "get-the-lead-out";
        $this->assertSame("get-the-lead-out", $environment->getUserAgent());
    }
    public function testUserAgentFail()
    {
        $environment = $this->getGlobalEnvironment();
        unset($_SERVER["USER_AGENT"]);
        $this->assertSame("", $environment->getUserAgent());
    }
    public function testUserAgentCache()
    {
        $environment = $this->getGlobalEnvironment();
        $_SERVER["USER_AGENT"] = "get-the-lead-out";
        $this->assertSame("get-the-lead-out", $environment->getUserAgent());
        unset($_SERVER["USER_AGENT"]);
        $this->assertSame("get-the-lead-out", $environment->getUserAgent());
    }
}
