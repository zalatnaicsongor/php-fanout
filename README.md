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
* firebase/php-jwt >=1.0.0 (retreived automatically via Composer)
* fanout/php-pubcontrol >=1.0.6 (retreived automatically via Composer)

Installation
------------

Using Composer: 'composer require fanout/fanout' 

Manual: ensure that php-jwt and php-pubcontrol have been included and require the following files in php-fanout:

```PHP
require 'php-fanout/src/jsonobjectformat.php';
require 'php-fanout/src/fanout.php';
```

Asynchronous Publishing
-----------------------

In order to make asynchronous publish calls pthreads must be installed. If pthreads is not installed then only synchronous publish calls can be made. To install pthreads recompile PHP with the following flag: '--enable-maintainer-zts'

Also note that since a callback passed to the publish_async methods is going to be executed in a separate thread, that callback and the class it belongs to are subject to the rules and limitations imposed by the pthreads extension.

See more information about pthreads here: http://php.net/manual/en/book.pthreads.php

Usage
------------

```PHP
<?php

$fanout = new Fanout('<realm>', '<realmkey>');
$fanout->publish('<channel>', 'Test publish!');

// Use publish_async for async publishing only if pthreads are installed:
// $fanout->publish_async('<channel>', 'Test async publish!', null, null,
//         'callback');
?>
```
