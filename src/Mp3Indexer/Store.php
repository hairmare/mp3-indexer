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
 * store data in semantic mediawiki
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
     * @param sfEventDispatcher      $dispatcher main event dispatcher
     * @param sfEvent                $logEvent   logger event
     * @param Mp3Indexer_MwApiClient $apiClient  api client to mediawiki
     */
    public function __construct(
        sfEventDispatcher $dispatcher,
        sfEvent $logEvent,
        Mp3Indexer_MwApiClient $apiClient
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.data', array($this, 'createOrUpdate'));
        $this->_logEvent = $logEvent;
        $this->_apiClient = $apiClient;
    }
    
    /**
     * Add an output mapper to maps.
     * 
     * @param Mp3Indexer_Map_SemanticMediawiki $map mapper instance
     * 
     * @return void
     */
    public function addMap(Mp3Indexer_Map_SemanticMediawiki $map)
    {
        $this->_maps[] = $map;
    }

    /**
     * make sure data is in database by using all mappers
     *
     * @param sfEvent $event triggering data laden event
     *
     * @return void
     */
    public function createOrUpdate(sfEvent $event)
    {
        $file = $event->file;
        $data = $event->data;
        $data['file'] = $file;
        $path = dirname($file);

        try {
            // create new file entry
            if (empty($path)) {
                throw new RuntimeException("empty path detected");
            }
            if (empty($data)) {
                throw new RuntimeException("no data in event");
            }
            
            foreach ($this->_maps AS $map) {
                $map->setData($data);
                $target = $map->getTarget();
                
                if ($target) {
                    $this->_apiClient->sfautoedit(
                        $map::MW_FORM, 
                        $target, 
                        $map->getQuery()
                    );
                }
            }
        } catch (Exception $e) {
            // trigger error log event
            $event = clone $this->_logEvent;
            $event->type = 'error';
            $event->message = $e->getMessage();
            $this->_dispatcher->notify($event);
            return false;
        }
        return true;
    }

}
