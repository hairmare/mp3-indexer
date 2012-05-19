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
 * factory for getting reader instances
 *
 * @category Reader
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_ReaderImplFactory
{
    const READER_CLASSNAME = 'Zend_Media_Id3v2';

    /**
     * open a file with READER_CLASSNAME
     *
     * @param String $file File to open
     *
     * @return self::READER_CLASSNAME
     */
    static function getReader($file)
    {
        $readerClass = Mp3Indexer_ReaderImplFactory::READER_CLASSNAME:
        return new $readerClass($file);
    }
}
