<?php
/**
 * runtime tests
 *
 * PHP Version 5
 *
 * @category  Test
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2012 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

require_once __DIR__.'/../../../src/Mp3Indexer/Map/SemanticMediawiki.php';
require_once __DIR__.'/../../../src/Mp3Indexer/Map/AudioTrack.php';

/**
 * Test class for Mp3Indexer_Map_AudioTrack.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Map_AudioFileTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_Map_AudioTrack
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->object = new Mp3Indexer_Map_AudioTrack;
    }
    
    /**
     * test getTarget Method
     *
     * @return void
     */
    public function testGetTarget()
    {
        $data = array(
            'file' => 'testdir/testfile'
        );
        $this->object->setData($data);
        
        // expect a md5sum of the file location 
        $this->assertEquals(
            $this->object->getTarget(),
            '88c35f93367ba1e9e388d93a8db92069' // == md5sum('testdir/testfile')
        );
    }
    
    /**
     * test getQuery Method
     * 
     * @return void
     */
    public function testGetQuery()
    {
        $data = array(
            'file' => 'testdir/testfile'
        );
        $this->object->setData($data);
        $this->assertEquals(
            $this->object->getQuery(),
            array('AudioTrack[Locator]=' => 'testdir/testfile')
        );
    }
}