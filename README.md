php-fanout
============

Author: Konstantin Bokarius <kon@fanout.io>

A PHP convenience library for publishing messages to Fanout.io using the EPCP protocol. 

License
-------

php-fanout is offered under the MIT license. See the LICENSE file.

Requirements
------------

* openssl
* curl
* pthreads (required for asynchronous publishing)
* php-jwt (retreived automatically via Composer)
* php-pubcontrol (retreived automatically via Composer)

Installation
------------

Using Composer: 'composer require fanout/pubcontrol' 

Manual: ensure that php-jwt and php-pubcontrol have been included and require the following files in php-fanout:

```PHP
require 'php-fanout/src/jsonobjectformat.php';
require 'php-fanout/src/fanout.php';
```

Usage
------------

```PHP
<?php

require 'vendor/autoload.php';

$fanout = new Fanout('<realm>', '<realmkey>');
$fanout->publish('<channel>', 'Test publish!');
$fanout->publish_async('<channel>', 'Test async publish!', null, null,
        'callback');
?>
```
