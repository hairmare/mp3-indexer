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
     * cache for prepared statements
     *
     * @var Array
     */
    private $_stmts = array();

    /**
     * create store and register events
     *
     * @param sfEventDispatcher $dispatcher main event dispatcher
     * @param PDO               $pdo        database connection
     * @param sfEvent           $logEvent   logger event
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
        if (!$this->_stmts) {
            $this->_stmts = $this->_prepareStatements();
        }
        $stmts = $this->_stmts;
        $file = $event->file;
        $data = $event->data;
        $path = dirname($file);
        $name = basename($file);

        $this->_pdo->beginTransaction();

        try {
            // create new file entry
            if (empty($path)) {
                throw new RuntimeException("empty path detected");
            }
            $stmts['file.insert']->bindParam('path', $path);
            $stmts['file.insert']->bindParam('name', $name);

            if (empty($data)) {
                throw new RuntimeException("no data in event");
            }

            $stmts['file.insert']->execute();

            $lastInsertId = $this->_pdo->lastInsertId();

            foreach ($data AS $value) {
                $this->_insertTags($value, $lastInsertId);
            }
        } catch (Exception $e) {
            $this->_pdo->rollback();
            // trigger error log event
            $event = clone $this->_logEvent;
            $event->type = 'error';
            $event->message = $e->getMessage();
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

    /**
     * convert and insert found tags
     *
     * @param Object  $value  a Zend_Media_* instance
     * @param Integer $fileId id of corresponding file record
     *
     * @return void
     */
    private function _insertTags($value, $fileId)
    {
        $stmts = $this->_stmts;
        $tagName = $value->getIdentifier();

        /* delegate datampping 
        $event = clone $this->_filterEvent;
        $this->_dispatcher->filter($event, $value);
        $value = $event->getReturnValue();
        */

        if (is_a($value, 'Zend_Media_Id3_TextFrame')) {
            $tagValues = $value->getTexts();
        } else if (is_a($value, 'Zend_Media_Id3_LinkFrame')) {
            $tagValues = array($value->getLink());
        } else if (is_a($value, 'Zend_Media_Id3_Frame_Ufid')) {
            // @todo implement serious loading
            $tagValues = array($value->getOwner());
        } else if (is_a($value, 'Zend_Media_Id3_Frame_Apic')) {
            // @todo implement picture loading
            $tagValues = array($value->getImageType());
        } else if (is_a($value, 'Zend_Media_Id3_Frame_Comm')) {
            // @todo implement serious loading
            $tagValues = array(
                $value->getDescription().' : '.$value->getText()
            );
        } else if (is_a($value, 'Zend_Media_Id3_Frame_Priv')) {
            // @todo do we need all these
            $tagValues = array(
                $value->getOwner().' : '.$value->getData()
            );
        } else {
            $tagValues = array(var_export($value, true));
        }
        foreach ($tagValues AS $text) {
            $stmts['id3.insert']->bindParam(
                'audioFile_id',
                $fileId
            );
            $stmts['id3.insert']->bindParam('tag', $tagName);
            $stmts['id3.insert']->bindParam('value', $text);
            $stmts['id3.insert']->execute();
        }
    }
}
