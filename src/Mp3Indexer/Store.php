<?php
/**
 * store found data
 *
 * PHP Version 5
 *
 * @category  Store
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * store data somehow
 *
 * @category Store
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Store
{
    /**
     * create store and register events
     *
     * @param sfEventDispatcher $dispatcher main event dispatcher
     *
     * @return void
     */
    public function __construct(
        sfEventDispatcher $dispatcher
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.data', array($this, 'createOrUpdate'));
    }

    /**
     * make sure data is in database
     *
     * @param sfEvent $event triggering data laden event
     *
     * @return void
     */
    public function createOrUpdate(sfEvent $event)
    {
        $file = $event['file'];
        $data = $event['data'];
        var_dump($data);
    }
}
