<?php
/**
 * simple stdout log
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
     * create logger and register events
     *
     * @param sfEventDispatcher $dispatcher main event dispatcher
     *
     * @return void
     */
    public function __construct(
        sfEventDispatcher $dispatcher
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('log', array($this, 'logEvent'));
    }

    /**
     * cat log message to stdout
     *
     * @param sfEvent $event log event
     *
     * @return void
     */
    public function logEvent(sfEvent $event)
    {
        if ($event['type'] == 'error') {
            printf("Message: %s\n", $event['message']);
        }
    }
}
