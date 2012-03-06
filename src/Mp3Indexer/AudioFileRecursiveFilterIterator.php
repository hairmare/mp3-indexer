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
     * only allow folders and accepted files
     *
     * @return Boolean
     */
    public function accept()
    {
         return $this->hasChildren() || in_array(
             $this->current()->getExtension(),
             self::$FILTERS,
             true
         );
    }

}

