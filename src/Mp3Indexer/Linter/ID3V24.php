<?php
/**
 * id3 v24 requiring linter
 *
 * PHP Version 5
 *
 * @category  Linter
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * only allow files with id3v24 tag
 *
 * @category Linter
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Linter_ID3V24
{
    /**
     * create linter and register event listeners
     *
     * @param sfEventDispatcher $dispatcher main event dispatcher
     *
     * @return void
     */
    public function __construct(
        sfEventDispatcher $dispatcher
    ) {
        $this->_dispatcher = $dispatcher;
        $this->_dispatcher->connect('mp3scan.lint', array($this, 'lint'));
    }

    /**
     * lint file from event
     *
     * @param sfEvent     $event triggering event
     * @param SplFileInfo $file  file to lint
     *
     * @return Mixed file or false
     */
    public function lint($event, SplFileInfo $file)
    {
        $this->_event = $event;
        if (id3_get_version($file->getPathname()) != ID3_V2_4 ) {
            return false;
        }
        return $file;
    }

}
