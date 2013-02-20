<?php
/**
 * log interface
 *
 * PHP Version 5
 *
 * @category  Log
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * log interface
 *
 * @category Log
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
interface Mp3Indexer_Log_Interface
{
    /**
     * Logs a String type message.
     * 
     * @param String $message string to log
     * 
     * @return void
     */
    public function logString($message);
}
