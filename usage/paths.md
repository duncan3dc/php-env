---
layout: default
title: Paths
permalink: /usage/paths/
api: Env
---

Once you've [defined your root path](../../setup/#paths) you can get relative paths like so:

~~~php
$cachePath = Env::path("cache");
~~~

There's also a wrapper for [realpath()](http://php.net/manual/en/function.realpath.php):

~~~php
$imagePath = Env::realpath("cache/images/small");
~~~
