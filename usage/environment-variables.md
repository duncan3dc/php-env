---
layout: default
title: Environment Variables
permalink: /usage/environment-variables/
api: Env
---

Once you've [defined the source](../../setup/#environment-variables) of your environment variables there are simple methods to work with them:

~~~php
# Retrieve a variable (or null if it doesn't exist)
$username = Env::getVar("git-username");
~~~

~~~php
# Retrieve a variable (but throw an exception if it doesn't exist)
$shouldDeleteDirectories = Env::requireVar("delete-directories-mode");
~~~

~~~php
# Override a variable (for the duration of this request only, does not persist)
Env::setVar("version", "DEV");
~~~
