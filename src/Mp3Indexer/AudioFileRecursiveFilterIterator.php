<?php
/**
 * file extension filter
 *
 * PHP Version 5
 *
 * @category  Scanner
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * Filter for distinguishing audio files
 *
 * @category Scanner
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_AudioFileRecursiveFilterIterator extends RecursiveFilterIterator
{
    public static $FILTERS = array(
        'mp3',
    );

    /**
     * allow folders and check files
     *
     * @return Boolean
     */
    public function accept()
    {
        if ($this->hasChildren()) {
            return true;
        }
        return $this->_hasFileMatch();
    }
    
    /**
     * only allow accepted files 
     *
     * @return Boolean
     */
    private function _hasFileMatch()
    {
        // @codeCoverageIgnoreStart
        // I am simply stumped on how to seriously test this
        // this horrible ->current() logic without resorting
        // to awful stuff, meh
        return in_array(
            strtolower($this->current()->getExtension()),
            self::$FILTERS,
            true
        );
        // @codeCoverageIgnoreEnd
    }
}

