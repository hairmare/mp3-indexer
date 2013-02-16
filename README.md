php based mp3 indexer
=====================

This is a PHP based MP3 indexer that stores the results of
scanning a directory tree containing MP3 Files into a
semantic mediawiki instance.

This is a fast enough that should be able to scan large amounts 
of files without tripping over itself as many indexers i tried 
so far did with my corpus.

This is still in its infancy and it still needs a lot of work
to be fully functional.

You will need to install php-reader and checkout submodules.

Create a localConf.php with the following values to try it out:

```php
<?php

$sc['mp3root'] = '/mnt/musicdisc/';
$sc['mw.apiurl'] = '';
$sc['mw.username'] = 'root';
$sc['mw.password'] = '';
$sc['mw.domain'] = '';
```