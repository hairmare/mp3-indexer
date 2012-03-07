<?php
/**
 * file reader
 *
 * PHP Version 5
 *
 * @category  Reader
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * class for reading a file
 *
 * @category Reader
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Reader
{
    /**
     * create reader and register event listeners
     *
     * @param sfEventDispatcher $dispatcher main event dispatcher
     * @param sfEvent           $linter     filter event for linting
     * @param sfEvent           $dataEvent  event for storage dispatch
     * @param sfEvent           $logEvent   logging event
     *
     * @return void
     */
    public function __construct (
        sfEventDispatcher $dispatcher,
        sfEvent $linter,
        sfEvent $dataEvent,
        sfEvent $logEvent
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.file', array($this, 'read'));
        $this->_linter = $linter;
        $this->_dataEvent = $dataEvent;
        $this->_logEvent = $logEvent;
    }

    /**
     * Read a file 
     *
     * @param sfEvent $event triggering event
     *
     * @return void
     */
    public function read(sfEvent $event)
    {
        $file = $event['file'];

        // lint them files
        $event = clone $this->_linter;
        $this->_dispatcher->filter($event, $file);

        if ($event->getReturnValue()) {
            $data = id3_get_tag($file->getPathname(), ID3_V2_4);

            $event = clone $this->_dataEvent;
            $event['file'] = $file;
            $event['data'] = $data;
            $this->_dispatcher->notify($event);
        } else {
            $event = clone $this->_logEvent;
            $event['type'] = 'warning';
            $event['message'] = 'Reader skipped file';
            $event['file'] = $file;
            $this->_dispatcher->notify($event);
        }
    }
}
