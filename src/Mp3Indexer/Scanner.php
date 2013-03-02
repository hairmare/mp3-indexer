<?php
/**
 * directory scanner
 *
 * PHP Version 5
 *
 * @category  Scanner
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * high level api for recursing over found audio files
 *
 * @category Scanner
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Scanner
{
    /**
     * create scanner
     *
     * @param RecursiveIteratorIterator       $iterator   iterator to recurse over
     * @param sfEventDispatcher               $dispatcher main event dispatcher
     * @param sfEvent                         $event      file detected event
     * @param Mp3Indexer_Log_Client_Interface $log        log client instance
     */
    public function __construct(
        RecursiveIteratorIterator $iterator,
        sfEventDispatcher $dispatcher,
        sfEvent $event,
        Mp3Indexer_Log_Client_Interface $log
    ) {
        $this->_iterator = $iterator;
        $this->_event = $event;
        $this->_dispatcher = $dispatcher;
        $this->_log = $log;
    }

    /**
     * recurse over iterator
     *
     * @return void
     */
    public function scan()
    {
        $this->_log->log("Starting recursive scan");
        $this->_recurse($this->_iterator);
    }

    /**
     * recurse over a nodes children or dispatch event
     *
     * @param RecursiveIterator $root iterator to recurse
     *
     * @return void
     */
    private function _recurse(RecursiveIteratorIterator $root)
    {
        foreach ($root AS $file) {
            // create event and notify on audio files
            $event = clone $this->_event;
            $event['file'] = $file;
            $this->_dispatcher->notifyUntil($event);
        }
    }
    
}
