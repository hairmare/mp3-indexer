<?php

require_once dirname(__FILE__).'/../lib/sf-dependency-injection/lib/sfServiceContainerAutoloader.php';
require_once dirname(__FILE__).'/../lib/sf-event-dispatcher/lib/sfEventDispatcher.php';
require_once dirname(__FILE__).'/Mp3Indexer/AudioFileRecursiveFilterIterator.php';
require_once dirname(__FILE__).'/Mp3Indexer/Scanner.php';
require_once dirname(__FILE__).'/Mp3Indexer/Reader.php';
require_once dirname(__FILE__).'/Mp3Indexer.php';

sfServiceContainerAutoloader::register();
 
// setup dependency injection
$sc = new sfServiceContainerBuilder(array(
    'mp3root' => $_SERVER['HOME']
));

$sc->register('dispatcher', 'sfEventDispatcher');

// creating iterators to find audio files
$sc->register('musiciterator', 'RecursiveDirectoryIterator')
   ->addArgument('%mp3root%');
$sc->register('audiofilteriterator', 'Mp3Indexer_AudioFileRecursiveFilterIterator')
   ->addArgument(new sfServiceReference('musiciterator'));
$sc->register('mp3iteratoriterator', 'RecursiveIteratorIterator')
   ->addArgument(new sfServiceReference('audiofilteriterator'))
   ->addArgument(RecursiveIteratorIterator::SELF_FIRST);

// some events that folks may clone
$sc->register('mp3fileevent', 'sfEvent')
   ->addArgument(new stdClass) // empty context because i dont care
   ->addArgument('mp3scan.file');
$sc->register('mp3lintevent', 'sfEvent')
   ->addArgument(new stdClass) // empty context because i dont care
   ->addArgument('mp3scan.lint');
// and my workhorses
$sc->register('mp3scanner', 'Mp3Indexer_Scanner')
   ->addArgument(new sfServiceReference('mp3iteratoriterator'))
   ->addArgument(new sfServiceReference('dispatcher'))
   ->addArgument(new sfServiceReference('mp3fileevent'));
$sc->register('mp3reader', 'Mp3Indexer_Reader')
   ->addArgument(new sfServiceReference('dispatcher'))
   ->addArgument(new sfServiceReference('mp3lintevent'));
// as well as something to tie this together
$sc->register('mp3indexer', 'Mp3Indexer')
   ->addArgument(new sfServiceReference('mp3scanner'))
   ->addArgument(new sfserviceReference('mp3reader'));
