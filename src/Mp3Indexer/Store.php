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
	var $_mwForm = 'AudioTrack';
    var $_templateMap = array(
      'TALB' => 'AudioTrack[IsTrackOf]',
      'TIT2' => 'AudioTrack[TrackName]',
      'TPE1' => 'AudioTrack[HasCreator]'
    );
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
        $path = dirname($file);
        $name = basename($file);

        try {
            // create new file entry
            if (empty($path)) {
                throw new RuntimeException("empty path detected");
            }
            if (empty($data)) {
                throw new RuntimeException("no data in event");
            }
            $tags = array();

            foreach ($data AS $value) {
                $name = $value->getIdentifier();
                $tags[$name] = $this->_getSimpleValue($value);
            }
            $artist = trim($tags['TPE1'][0]);
            $album = trim($tags['TALB'][0]);
            $track = trim($tags['TIT2'][0]);
            
			if ($album) {
                $target = $track.' von '.$artist.' auf '.$album.' (Track)';
            } else {
                $target = $track.' von '.$artist.' (Track)';
            }

            $query = array('AudioTrack[Locator]=file:///' => $file);
            $replace = array(
            	'[' => '(',
            	']' => ')',
            	'#' => '',
            	'<' => '(',
            	'>' => ')',
            	'|' => '-',
            	'{' => '(',
            	'}' => ')'
            );
            foreach ($tags AS $name => $data) {
                if (!empty($this->_templateMap[$name])) {
                    $query[$this->_templateMap[$name].'='] = strtr(trim($data[0]), $replace);
                }
            }
            $query['AudioTrack[IsTrackOf]'] = $album.' von '.$artist. ' (Album)';
            $target = strtr($target, $replace);
            
            //echo PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.PHP_EOL.$target.PHP_EOL;
            $this->_apiClient->sfautoedit($this->_mwForm, $target, $query);
            echo '.';
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

    /**
     * convert and insert found tags
     *
     * @param Object  $value  a Zend_Media_* instance
     *
     * @return void
     */
    private function _getSimpleValue($value)
    {
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
        return $tagValues;
    }
}
