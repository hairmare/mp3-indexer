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

ini_set(
    'include_path',
    dirname(__FILE__).'/../lib/php-reader/library/:'.ini_get('include_path')
);

require_once 'Zend/Media/Id3v2.php';
require_once dirname(__FILE__).
    '/../lib/sf-dependency-injection/lib/sfServiceContainerAutoloader.php';
require_once dirname(__FILE__).
    '/../lib/sf-event-dispatcher/lib/sfEventDispatcher.php';
require_once dirname(__FILE__).'/Mp3Indexer/AudioFileRecursiveFilterIterator.php';
require_once dirname(__FILE__).'/Mp3Indexer/Linter/ID3V24.php';
require_once dirname(__FILE__).'/Mp3Indexer/Log/Interface.php';
require_once dirname(__FILE__).'/Mp3Indexer/Log/Stdout.php';
require_once dirname(__FILE__).'/Mp3Indexer/MwApiClient.php';
require_once dirname(__FILE__).'/Mp3Indexer/Map/SemanticMediawiki.php';
require_once dirname(__FILE__).'/Mp3Indexer/Map/AudioTrack.php';
require_once dirname(__FILE__).'/Mp3Indexer/Curl.php';
require_once dirname(__FILE__).'/Mp3Indexer/Scanner.php';
require_once dirname(__FILE__).'/Mp3Indexer/ReaderImplFactory.php';
require_once dirname(__FILE__).'/Mp3Indexer/Reader.php';
require_once dirname(__FILE__).'/Mp3Indexer/Store.php';
require_once dirname(__FILE__).'/Mp3Indexer.php';

sfServiceContainerAutoloader::register();
 
// setup dependency injection
$sc = new sfServiceContainerBuilder(
    array(
        'mp3root' => $_SERVER['HOME'].'/Music',
        'mw.apiurl'=> '',
        'mw.username' => '',
        'mw.password' => '',
        'mw.domain' => null,
        'eyeD3.bin' => 'eyeD3',
    )
);

ini_set('memory_limit', -1);

// pull in additional config
if (file_exists(dirname(__FILE__).'/../localConf.php')) {
    include_once dirname(__FILE__).'/../localConf.php';
}

$sc->register('dispatcher', 'sfEventDispatcher');

// oo interface to curl
$sc->register('curl', 'Mp3Indexer_Curl');

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
$sc->register('logevent', 'sfEvent')
    ->addArgument(new stdClass)
    ->addArgument('log');

// wrapper for reading mps data
$sc->register('readerimplfactory', 'Mp3Indexer_ReaderImplFactory');

// access to mediawiki
$sc->register('mwapiclient', 'Mp3Indexer_MwApiClient')
    ->addArgument('%mw.apiurl%')
    ->addArgument(new sfServiceReference('curl'))
    ->addMethodCall(
        'login',
        array('%mw.username%', '%mw.password%', '%mw.domain%')
    );

// mappers for mediawiki pages
$sc->register('mapaudiotrack', 'Mp3Indexer_Map_AudioTrack');

// and my workhorses
$sc->register('mp3scanner', 'Mp3Indexer_Scanner')
    ->addArgument(new sfServiceReference('audiofilteriterator'))
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('mp3fileevent'));
$sc->register('mp3reader', 'Mp3Indexer_Reader')
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('mp3lintevent'))
    ->addArgument(new sfServiceReference('mp3dataevent'))
    ->addArgument(new sfServiceReference('logevent'))
    ->addArgument(new sfServiceReference('readerimplfactory'));
$sc->register('mp3store', 'Mp3Indexer_Store')
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('logevent'))
    ->addArgument(new sfServiceReference('mwapiclient'))
    ->addMethodCall(
        'addMap',
        array(new sfServiceReference('mapaudiotrack'))
    );

// linting
$sc->register('mp3lint.id3v34', 'Mp3Indexer_Linter_ID3V24')
    ->addArgument(new sfServiceReference('dispatcher'));

// loggers
$sc->register('logstdout', 'Mp3Indexer_Log_Stdout')
    ->addArgument(new sfServiceReference('dispatcher'));

// as well as something to tie everything together
$sc->register('mp3indexer', 'Mp3Indexer')
    ->addArgument(new sfServiceReference('mp3scanner'))
    ->addArgument(new sfServiceReference('mp3reader'))
    ->addArgument(new sfServiceReference('mp3store'))
    ->addArgument(array())
    ->addMethodCall(
        'addLogger',
        array(new sfServiceReference('logstdout'))
    );
