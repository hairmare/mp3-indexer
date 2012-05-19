php based mp3 indexer
=====================

This is a fast php based mp3 indexer that should be able to
scan large amounts of files without tripping over itself as
many indexers i tried so far do with my corpus.

This is still in its infancy and it only dumps the data into
an sql database without performing much indexing.

You will need to install php-reader 

Create a localConf.php with the following values to try it out:

<?php

$sc['mp3root'] = '/mnt/musicdisc/';
$sc['db.dsn'] = 'mysql:host=localhost;port=3306;dbname=mp3-indexer';
$sc['db.user'] = 'root';
$sc['db.pass'] = '';
