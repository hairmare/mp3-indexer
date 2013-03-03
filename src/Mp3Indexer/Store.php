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
     * @param Mp3Indexer_Log_Client  $logClient  log client instance
     * @param Mp3Indexer_MwApiClient $apiClient  api client to mediawiki
     */
    public function __construct(
        sfEventDispatcher $dispatcher,
        Mp3Indexer_Log_Client $logClient,
        Mp3Indexer_MwApiClient $apiClient
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.data', array($this, 'createOrUpdate'));
        $this->_log = $logClient;
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
        $file = $event['file'];
        $data = $event['data'];
        $path = dirname($file);

        try {
            $this->_log->log(
                sprintf(
                    "creating or updating record for file %s", 
                    $file
                )
            );
            
            // create new file entry
            if (empty($path)) {
                throw new RuntimeException("empty path detected");
            }
            if (empty($data)) {
                throw new RuntimeException("no data in event");
            }

            $data['file'] = $file;
            foreach ($this->_maps AS $map) {
                $map->setData($data);
                
                foreach ($map->getElements() AS $target => $query) {
                    $this->_apiClient->sfautoedit(
                        $map::MW_FORM, 
                        $target, 
                        $query
                    );
                }
            }
        } catch (Exception $e) {
            $this->_log->debug($e->getMessage());
            return false;
        }
        return true;
    }

}
