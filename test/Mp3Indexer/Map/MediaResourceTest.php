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
require_once __DIR__.'/../../../src/Mp3Indexer/Map/MediaResource.php';

/**
 * Test class for Mp3Indexer_Map_AudioTrack.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Map_MediaResourceTest extends PHPUnit_Framework_TestCase
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
        
        $this->object = new Mp3Indexer_Map_MediaResource();
    }

    /**
     * test getElements Method
     *
     * @return void
     */
    public function testGetElements()
    {
        $data = array(
            'file' => 'testdir/testsubdir/testfile'
        );
        $this->object->setData($data);
        $this->object->setNamespace('Music');
        
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
            array(
                'Music:testdir' => array(
                    'MediaResource[Locator]=' => 'testdir/',
                ),
                'Music:testdir/testsubdir' => array(
                    'MediaResource[Locator]=' => 'testdir/testsubdir',
                )
            ),
            $this->object->getElements()
        );
    }
}