<?php
/**
 * log client
 *
 * PHP Version 5
 *
 * @category  Log
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * log client
 *
 * @category Log
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Log_Client implements Mp3Indexer_Log_Client_Interface
{
    /**
     * create log client
     * 
     * @param sfEventDispatcher $eventDispatcher event dispatcher for sending log events
     * @param sfEvent           $logEvent        clonable event to dispatch
     * 
     * @return void
     */
    public function __construct(sfEventDispatcher $eventDispatcher, sfEvent $logEvent)
    {
    }

    /**
     * Log normal log messages
     *
     * @param String $message message string.
     *
     * @return void
     */
    public function log($message)
    {
        
    }

    /**
     * Log informational messages
     *
     * Informational messages contain data encountered during indexing.
     *
     * @param String $message message string
     *
     * @return void
     */
    public function info($message)
    {
        
    }
    
    /**
     * Log debug messages
     *
     * Debug messages contain data related to setup, teardown and exception handling.
     *
     * @param String $message
     *
     * @return void
     */
    public function debug($message)
    {
        
    }
    
    /**
     * increase verbosity
     * 
     * @return void
     */
    public function setVerbose()
    {
        
    }
    
    /**
     * dispatch a log event for Mp3Indexer_Log_Interface clients
     * 
     * @param String  $message message string
     * @param Integer $level   message level
     */
    private function _dispatchLog($message, $level)
    {
        
    }
}