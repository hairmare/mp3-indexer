<?php
/**
 * simple stdout log
 *
 * PHP Version 5
 *
 * @category  Log
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012-2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * log everything to stdout
 *
 * @category Log
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Log_Stdout implements Mp3Indexer_Log_Interface
{

    /**
     * cat log message to stdout
     *
     * @param String $message log event
     *
     * @return void
     */
    public function logString($message)
    {
        printf("Message: %s\n", $message);
    }
}
