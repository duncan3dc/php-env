---
layout: default
title: Setup
permalink: /setup/
---

All classes are in the `duncan3dc\Env` namespace.

~~~php
require_once __DIR__ . "vendor/autoload.php";

use duncan3dc\Env\Env;
~~~

## Paths

If you want to use the path based features of the project then you should define the root path.  
By default the `Env` class assumes your project root is the parent of the composer `vendor` directory, however you might want to override it:

~~~php
# Use the webservers document path (found in $_SERVER["DOCUMENT_ROOT"])
Env::usePath(Env::PATH_DOCUMENT_ROOT);
~~~

~~~php
# Use the initial PHP script this request originated from ($_SERVER["PHP_SELF"])
Env::usePath(Env::PATH_PHP_SELF);
~~~

~~~php
# Use a specific hard-coded path
Env::usePath(__DIR__);
~~~

## Environment Variables

You can load environment variables from the global `$_ENV` array:

~~~php
Env::setEnvironment(new \duncan3dc\Env\GlobalEnvironment);
~~~

Or from a local YAML encoded file:

~~~php
$path = Env::path("data/env.yaml");
Env::setEnvironment(new YamlEnvironment($path));
~~~

Or from a local JSON encoded file:

~~~php
$path = Env::path("data/env.json");
Env::setEnvironment(new JsonEnvironment($path));
~~~

Or from [any source you like](../usage/custom-environment)
