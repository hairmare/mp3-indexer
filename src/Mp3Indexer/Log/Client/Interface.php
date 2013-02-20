<?php
/**
 * log client interface
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
 * log client interface
 *
 * @category Log
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
interface Mp3Indexer_Log_Client_Interface
{
    /**
     * Log normal log messages
     * 
     * @param String $message message string.
     * 
     * @return void
     */
    public function log($message);
    
    /**
     * Log informational messages
     * 
     * Informational messages contain data encountered during indexing.
     * 
     * @param String $message message string
     * 
     * @return void
     */
    public function info($message);
    
    /**
     * Log debug messages
     * 
     * Debug messages contain data related to setup, teardown and exception handling.
     * 
     * @param String $message
     * 
     * @return void
     */
    public function debug($message);
}