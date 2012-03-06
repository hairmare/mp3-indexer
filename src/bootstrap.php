<?php
/**
 * bootstrap the indexer
 *
 * PHP Version 5
 *
 * @category  Bootstrap
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */


require_once dirname(__FILE__).
    '/../lib/sf-dependency-injection/lib/sfServiceContainerAutoloader.php';
require_once dirname(__FILE__).
    '/../lib/sf-event-dispatcher/lib/sfEventDispatcher.php';
require_once dirname(__FILE__).'/Mp3Indexer/AudioFileRecursiveFilterIterator.php';
require_once dirname(__FILE__).'/Mp3Indexer/Linter/ID3V24.php';
require_once dirname(__FILE__).'/Mp3Indexer/Scanner.php';
require_once dirname(__FILE__).'/Mp3Indexer/Reader.php';
require_once dirname(__FILE__).'/Mp3Indexer/Store.php';
require_once dirname(__FILE__).'/Mp3Indexer.php';

sfServiceContainerAutoloader::register();
 
// setup dependency injection
$sc = new sfServiceContainerBuilder(
    array(
        'mp3root' => $_SERVER['HOME'],
        'eyeD3.bin' => 'eyeD3'
    )
);

$sc->register('dispatcher', 'sfEventDispatcher');

// creating iterators to find audio files
$sc->register('musiciterator', 'RecursiveDirectoryIterator')
    ->addArgument('%mp3root%');
$sc->register('audiofilteriterator', 'Mp3Indexer_AudioFileRecursiveFilterIterator')
    ->addArgument(new sfServiceReference('musiciterator'));

// some events that folks may clone
$sc->register('mp3fileevent', 'sfEvent')
    ->addArgument(new stdClass) // empty context because i dont care
    ->addArgument('mp3scan.file');
$sc->register('mp3lintevent', 'sfEvent')
    ->addArgument(new stdClass) // empty context because i dont care
    ->addArgument('mp3scan.lint');
$sc->register('mp3dataevent', 'sfEvent')
    ->addArgument(new stdClass)
    ->addArgument('mp3scan.data');
// and my workhorses
$sc->register('mp3scanner', 'Mp3Indexer_Scanner')
    ->addArgument(new sfServiceReference('audiofilteriterator'))
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('mp3fileevent'));
$sc->register('mp3reader', 'Mp3Indexer_Reader')
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('mp3lintevent'))
    ->addArgument(new sfServiceReference('mp3dataevent'));
$sc->register('mp3store', 'Mp3Indexer_Store')
    ->addArgument(new sfServiceReference('dispatcher'));
$sc->register('mp3lint.id3v34', 'Mp3Indexer_Linter_ID3V24')
    ->addArgument(new sfServiceReference('dispatcher'));
// as well as something to tie everything together
$sc->register('mp3indexer', 'Mp3Indexer')
    ->addArgument(new sfServiceReference('mp3scanner'))
    ->addArgument(new sfServiceReference('mp3reader'))
    ->addArgument(new sfServiceReference('mp3store'))
    ->addArgument(array(new sfServiceReference('mp3lint.id3v34')));
