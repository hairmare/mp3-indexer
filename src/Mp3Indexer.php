<?php
/**
 * main mp3 index runtime
 *
 * PHP Version 5
 *
 * @category  Application
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012-2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * main application class for indexing mp3 dirs
 *
 * @category Application
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer
{
    /**
     * create new indexer
     *
     * @param Mp3Indexer_Scanner $scanner highlevel scanning interface
     * @param Mp3Indexer_Reader  $reader  file reader
     * @param Mp3Indexer_Store   $store   storage handle
     * @param Array              $linters lots of Mp3Indexer_Linter_Interface
     *
     * @return void
     */
    public function __construct(
        Mp3Indexer_Scanner $scanner,
        Mp3Indexer_Reader $reader,
        Mp3Indexer_Store $store,
        $linters
    ) {
        $this->_scanner = $scanner;
        $this->_reader = $reader;
        $this->_store = $store;
        $this->_linters = $linters;
    }

    /**
     * tell scanner to run
     *
     * @return void
     */
    public function run()
    {
        $this->_scanner->scan();
    }
}
