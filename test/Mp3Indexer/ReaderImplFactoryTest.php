<?php
/**
 * runtime tests
 *
 * PHP Version 5
 *
 * @category  Test
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012-2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

require_once __DIR__.'/../../src/Mp3Indexer/ReaderImplFactory.php';

/**
 * Test class for Mp3Indexer_ReaderImplFactory.
 * Generated by PHPUnit on 2012-05-20 at 19:18:21.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_ReaderImplFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test for instances of registered reader class
     *
     * @covers Mp3Indexer_ReaderImplFactory::getReader
     *
     * @return void
     */
    public function testGetReader()
    {
        // setup quick and dirty mock
        Mp3Indexer_ReaderImplFactory::$READER_CLASSNAME 
            = 'Mp3Indexer_ReaderImplFactoryTest_Reader';

        $filename = 'testfile.php';

        // run stuff
        $reader = Mp3Indexer_ReaderImplFactory::getReader($filename);

        // check stuff
        $this->assertInstanceOf('Mp3Indexer_ReaderImplFactoryTest_Reader', $reader);
        $this->assertEquals($filename, $reader->file);
    }
}

/**
 * helper class for mocking the Zend_Media_Id3 interface
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_ReaderImplFactoryTest_Reader
{
    /**
     * same interface as the reader has
     * 
     * @param String $file file name
     */
    public function __construct($file)
    {
        $this->file = $file;
    }
}
