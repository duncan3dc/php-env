---
layout: default
title: Custom Environment
permalink: /usage/custom-environment/
api: EnvironmentInterface
---

If you want to use an environment variable source not available out of the box, you can implement the `EnvironmentInterface`:

~~~php
class GetEnvironment implements \duncan3dc\Env\EnvironmentInterface
{
    public function has($var)
    {
        return array_key_exists($var, $_GET);
    }


    public function get($var)
    {
        return $_GET[$var];
    }


    public function set($var, $value)
    {
        $_GET[$var] = $value;
    }
}
~~~

As most sources of environment variables boil down to a simple array, there's an `AbstractEnvironment` class available to make things even easier:

~~~php
class GetEnvironment extends \duncan3dc\Env\AbstractEnvironment
{
    protected function getVars()
    {
        return $_GET;
    }
}
~~~
