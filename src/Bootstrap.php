<?php
/**
 * bootstrap the indexer
 *
 * PHP Version 5
 *
 * @category  Bootstrap
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012-2013 - Alle Rechte vorbehalten
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

// basic autoloader for Mp3Indexer_ Classes
$autoLoader = function ($class) {
    if (substr($class, 0, 10) == 'Mp3Indexer') {
        include __DIR__.'/'.strtr($class, '_', '/').'.php';
    }
};
spl_autoload_register($autoLoader);
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
$sc->register('iteratoriterator', 'RecursiveIteratorIterator')
    ->addArgument(new sfServiceReference('audiofilteriterator'));

// some events that folks may clone
$sc->register('mp3fileevent', 'sfEvent')
    ->addArgument(new stdClass) // empty context because i dont care
    ->addArgument('mp3scan.file');
$sc->register('mp3dataevent', 'sfEvent')
    ->addArgument(new stdClass)
    ->addArgument('mp3scan.data');

// wrapper for reading mps data
$sc->register('readerimplfactory', 'Mp3Indexer_ReaderImplFactory');

// access to mediawiki
$sc->register('mwapiclient', 'Mp3Indexer_MwApiClient')
    ->addArgument('%mw.apiurl%')
    ->addArgument(new sfServiceReference('curl'))
    ->addArgument(new sfServiceReference('mp3logclient'))
    ->addMethodCall(
        'login',
        array('%mw.username%', '%mw.password%', '%mw.domain%')
    );

// mappers for mediawiki pages
$sc->register('mapaudiotrack', 'Mp3Indexer_Map_AudioTrack');
$sc->register('mapmediaresource', 'Mp3Indexer_Map_MediaResource');
$sc->register('mapartist', 'Mp3Indexer_Map_Artist')
    ->addMethodCall(
        'setArtistsFromSmw', 
        array(new sfServiceReference('mwapiclient'))
    );

// and my workhorses
$sc->register('mp3scanner', 'Mp3Indexer_Scanner')
    ->addArgument(new sfServiceReference('iteratoriterator'))
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('mp3fileevent'))
    ->addArgument(new sfServiceReference('mp3logclient'));
$sc->register('mp3reader', 'Mp3Indexer_Reader')
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('mp3dataevent'))
    ->addArgument(new sfServiceReference('mp3logclient'))
    ->addArgument(new sfServiceReference('readerimplfactory'));
$sc->register('mp3store', 'Mp3Indexer_Store')
    ->addArgument(new sfServiceReference('dispatcher'))
    ->addArgument(new sfServiceReference('mp3logclient'))
    ->addArgument(new sfServiceReference('mwapiclient'))
    ->addMethodCall(
        'addMap',
        array(new sfServiceReference('mapmediaresource'))
    )
    ->addMethodCall(
        'addMap',
        array(new sfServiceReference('mapaudiotrack'))
    )
    ->addMethodCall(
        'addMap',
        array(new sfServiceReference('mapartist'))
    );

// loggers
$sc->register('logstdout', 'Mp3Indexer_Log_Stdout')
    ->addArgument(new sfServiceReference('dispatcher'));

// log system
$sc->register('mp3logclient', 'Mp3Indexer_Log_Client')
    ->addMethodCall(
        'registerLog',
        array(new sfServiceReference('logstdout'))
    );

// as well as something to tie everything together
$sc->register('mp3indexer', 'Mp3Indexer')
    ->addArgument(new sfServiceReference('mp3scanner'))
    ->addArgument(new sfServiceReference('mp3reader'))
    ->addArgument(new sfServiceReference('mp3store'))
    ->addArgument(array());
