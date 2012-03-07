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
     * @param PDO               $pdo        database connection
     * @param sfEvent           $logEvent   logger event
     *
     * @return void
     */
    public function __construct(
        sfEventDispatcher $dispatcher,
        PDO $pdo,
        sfEvent $logEvent
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.data', array($this, 'createOrUpdate'));
        $this->_pdo = $pdo;
        $this->_logEvent = $logEvent;
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
        static $stmts = false;
        if (!$stmts) {
            $stmts = $this->_prepareStatements();
        }
        $file = $event['file'];
        $data = $event['data'];

        $this->_pdo->beginTransaction();

        try {
            // create new file entry
            $path = dirname($file);
            $stmts['file.insert']->bindParam('path', $path);
            $name = basename($file);
            $stmts['file.insert']->bindParam('name', $name);
            $stmts['file.insert']->execute();

            $lastInsertId = $this->_pdo->lastInsertId();
            foreach ($data AS $tag => $value) {
                $stmts['id3.insert']->bindParam(
                    'audioFile_id',
                    $lastInsertId
                );
                $stmts['id3.insert']->bindParam('tag', $tag);
                $stmts['id3.insert']->bindParam('value', $value);
                $stmts['id3.insert']->execute();
            }
        } catch (Exception $e) {
            $this->_pdo->rollback();
            // trigger error log event
            $event = clone $this->_logEvent;
            $event['type'] = 'error';
            $event['message'] = $e->getMessage();
            $this->_dispatcher->notify($event);
            return false;
        }
        $this->_pdo->commit();
        return true;
    }

    /**
     * prepare all the relevant statements
     *
     * @return void
     */
    private function _prepareStatements()
    {
        $stmts = array();
        $stmts['file.insert'] = $this->_pdo->prepare(
            '
                REPLACE INTO audioFile
                SET
                    path = :path,
                    name = :name;
            '
        );
        $stmts['id3.insert'] = $this->_pdo->prepare(
            '
                REPLACE INTO id3Record
                SET
                    audioFile_id = :audioFile_id,
                    tag = :tag,
                    value = :value;
            '
        );
        return $stmts;
    }
}
