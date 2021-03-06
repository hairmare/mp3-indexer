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
        $this->textFrameMock = $this
            ->getMockBuilder('Zend_Media_Id3_TextFrame')
            ->setMethods(
                array(
                    'getIdentifier',
                    'getTexts'
                )
            )
            ->getMock();
        
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
        
        $this->assertEquals(
            $this->object->getTarget(),
            'testdir/testfile'
        );
    }
    
    /**
     * test to see if getTarget honors setNamespace
     * 
     * @return void
     */
    public function testGetTargetWithNamespace()
    {
        $this->object->setNamespace('Musik');

        $data = array(
            'file' => 'testdir/testfile'
        );
        $this->object->setData($data);
        
        $this->assertEquals(
            $this->object->getTarget(),
            'Musik:testdir/testfile'
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
        
        $this->textFrameMock
            ->expects($this->atLeastOnce())
            ->method('getIdentifier')
            ->will($this->returnValue('TALB'));
        $this->textFrameMock
            ->expects($this->atLeastOnce())
            ->method('getTexts')
            ->will($this->returnValue(array('Hello World!')));
        
        $data[] = $this->textFrameMock;
        $data[] = $this->textFrameMock;
        $this->object->setData($data);
        
        $this->assertEquals(
            $this->object->getQuery(),
            array(
                'AudioTrack[Locator]=' => 'testdir/testfile',
                'AudioTrack[IsTrackOf]=' => 'Hello World!'
            )
        );
    }

    /**
     * test getElements Method
     *
     * @return void
     */
    public function testGetElements()
    {
        $data = array(
            'file' => 'testdir/testfile'
        );
        $this->object->setData($data);
        $this->assertEquals(
            $this->object->getQuery(),
            array('AudioTrack[Locator]=' => 'testdir/testfile')
        );
        
        $this->textFrameMock
            ->expects($this->atLeastOnce())
            ->method('getIdentifier')
            ->will($this->returnValue('TALB'));
        $this->textFrameMock
            ->expects($this->atLeastOnce())
            ->method('getTexts')
            ->will($this->returnValue(array('Hello World!')));
        
        $data[] = $this->textFrameMock;
        $data[] = $this->textFrameMock;
        $this->object->setData($data);
    
        $this->assertEquals(
            $this->object->getElements(),
            array(
                'testdir/testfile' => array(
                    'AudioTrack[Locator]=' => 'testdir/testfile',
                    'AudioTrack[IsTrackOf]=' => 'Hello World!'
                )    
            )
        );
    }
}