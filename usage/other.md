---
layout: default
title: Other
permalink: /usage/other/
api: Env
---

There are a few other environment related functions available:

~~~php
# Get the domain we're currently running under
$hostname = Env::getHostName();
~~~

~~~php
# Get the current server's hostname
$hostname = Env::getMachineName();
~~~

~~~php
# Get the git hash of the code base in our root path
$version = "2.0." . Env::getRevision(5);
~~~
