PHP based MP3 Indexer
=====================

This is a PHP based MP3 indexer that stores the results of
scanning a directory tree containing MP3 Files into a
semantic mediawiki instance.

This is a fast enough that should be able to scan large amounts 
of files without tripping over itself as many indexers i tried 
so far did with my corpus.

This is still in its infancy and it still needs a lot of work
to be fully functional.

Installation
------------

The recommended installation process is based on git for the 
time being.

You might like to install this into /usr/local like so.

```sh
cd /usr/local
git clone --depth 1 https://github.com/hairmare/mp3-indexer.git mp3-indexer
cd mp3-indexer
git submodule init
git submodule update
```

Now go to http://code.google.com/p/php-reader/, download the library and pop
it into the lib subdir where the git hosted dependencies where installed in
the last steps.

Configuration
-------------

Create a localConf.php file in /usr/local/mp3-indexer based on the following 
values:

```php
<?php
$sc['mp3root'] = '/mnt/musicdisc/';
$sc['mw.apiurl'] = 'http://wiki.example.com/wiki/api.php';
$sc['mw.username'] = 'Username';
$sc['mw.password'] = '';
$sc['mw.domain'] = '';
```

Usage
-----

```sh
./bin/mp3scan
```

Contributing
------------

Yes, please! Fork and pull-request away :)

Have a look at the github issues to make sure you are not working 
on something already being worked on.

Please create an issue if you plan on doing any large scale refactoring.

At the moment commits and releases get vetted by a private jenkins
setup. Please plan on seeing your pull requests through as I am 
usually rather stringent when it comes down to mess detection, 
codestyle and test coverage.

If you want to run the tests on you own these specs might help you:

```
php -v
PHP 5.3.18-pl0-gentoo (cli) (built: Feb 18 2013 23:33:12) 
Copyright (c) 1997-2012 The PHP Group
Zend Engine v2.3.0, Copyright (c) 1998-2012 Zend Technologies
    with Xdebug v2.1.2, Copyright (c) 2002-2011, by Derick Rethans

phpcs  --version
PHP_CodeSniffer version 1.3.3 (stable) by Squiz Pty Ltd. (http://www.squiz.net)

phpmd --version
PHPMD 1.1.0 by Manuel Pichler

phpunit --version
PHPUnit 3.6.0 by Sebastian Bergmann.
```