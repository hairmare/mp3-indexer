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
    const LVL_LOG = 0;
    const LVL_INFO = 1;
    const LVL_DEBUG = 2;
    
    private $_level = self::LVL_LOG;

    /**
     * Log normal log messages
     *
     * @param String $message message string.
     *
     * @return void
     */
    public function log($message)
    {
        $this->_dispatchLog($message, self::LVL_LOG);
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
        $this->_dispatchLog($message, self::LVL_INFO);
    }
    
    /**
     * Log debug messages
     *
     * Debug messages contain data related to setup, teardown and exception handling.
     *
     * @param String $message message string
     *
     * @return void
     */
    public function debug($message)
    {
        $this->_dispatchLog($message, self::LVL_DEBUG);
    }
    
    /**
     * increase verbosity
     * 
     * @return void
     */
    public function setVerbose()
    {
        $this->_level++;
    }
    
    /**
     * register a logger that gets messages
     * 
     * @param Mp3Indexer_Log_Interface $log log implementation
     * 
     * @return void
     */
    public function registerLog(Mp3Indexer_Log_Interface $log)
    {
        $this->_loggers[] = $log;
    }
    
    /**
     * dispatch a log event for Mp3Indexer_Log_Interface clients
     * 
     * @param String  $message message string
     * @param Integer $level   message level
     * 
     * @return void
     */
    private function _dispatchLog($message, $level)
    {
        $doLog = $this->_level >= $level;
        
        if ($doLog) {
            foreach ($this->_loggers AS $log) {
                $log->logString($message);
            }
        }
    }
}