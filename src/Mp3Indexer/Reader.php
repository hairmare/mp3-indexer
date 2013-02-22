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
     * @param sfEventDispatcher            $dispatcher    main event dispatcher
     * @param sfEvent                      $dataEvent     event for storage dispatch
     * @param Mp3Indexer_Log_Client        $logClient     log client instance
     * @param Mp3Indexer_ReaderImplFactory $readerFactory factory for getting readers
     * 
     * @return void
     */
    public function __construct (
        sfEventDispatcher $dispatcher,
        sfEvent $dataEvent,
        Mp3Indexer_Log_Client $logClient,
        Mp3Indexer_ReaderImplFactory $readerFactory
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.file', array($this, 'read'));
        $this->_dataEvent = $dataEvent;
        $this->_log = $logClient;
        $this->_readerFactory = $readerFactory;
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
        try {
            $file = $event['file'];
            
            $reader = $this->_readerFactory->getReader($file);
            
            $data = $reader->getFramesByIdentifier('*');

            $event = clone $this->_dataEvent;
            $event->offsetSet('file', $file);
            $event['data'] = $data;
            $this->_dispatcher->notify($event);
        } catch (Exception $e) {
            $this->_log->debug($e->getMessage());
        }
    }
}
