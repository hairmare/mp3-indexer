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
     * @param sfEvent                      $linter        filter event for linting
     * @param sfEvent                      $dataEvent     event for storage dispatch
     * @param sfEvent                      $logEvent      logging event
     * @param Mp3Indexer_ReaderImplFactory $readerFactory factory for getting readers
     * 
     * @return void
     */
    public function __construct (
        sfEventDispatcher $dispatcher,
        sfEvent $linter,
        sfEvent $dataEvent,
        sfEvent $logEvent,
        Mp3Indexer_ReaderImplFactory $readerFactory
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.file', array($this, 'read'));
        $this->_linter = $linter;
        $this->_dataEvent = $dataEvent;
        $this->_logEvent = $logEvent;
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
        $logError = false;
        $file = $event['file'];
        echo $file;

        // lint them files
        $event = clone $this->_linter;
        $this->_dispatcher->filter($event, $file);

        try {
            $reader = $this->_readerFactory->getReader($file);
            if ($event->getReturnValue()) {
                $data = $reader->getFramesByIdentifier('*');

                $event = clone $this->_dataEvent;
                $event['file'] = $file;
                $event['data'] = $data;
                $this->_dispatcher->notify($event);
            } else {
                $logError = true;
                $logMessage = 'lintering skipped file';
            }
        } catch (Exception $e) {
            $logError = true;
            $logMessage = get_class($e).':'.$e->getMessage();
        }

        if ($logError) {
            $event = clone $this->_logEvent;
            $event['type'] = 'warning';
            $event['message'] = $logMessage;
            $event['file'] = $file;
            $this->_dispatcher->notify($event);
        }
    }
}
