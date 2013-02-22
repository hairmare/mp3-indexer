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

require_once __DIR__.'/../../src/Mp3Indexer/Store.php';

/**
 * Test class for Mp3Indexer_Store.
 * Generated by PHPUnit on 2012-05-21 at 05:09:07.
 *
 * PHP Version 5
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_StoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_Store
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
        $this->dispatcherMock = $this
            ->getMockBuilder('sfEventDispatcher')
            ->getMock();
        $this->eventMock = $this
            ->getMockBuilder('sfEvent')
            ->disableOriginalConstructor()
            ->getMock();
        $this->mwApiClientMock = $this
            ->getMockBuilder('Mp3Indexer_MwApiClient')
            ->disableOriginalConstructor()
            ->setMethods(
                array(
                    'sfautoedit'
                )
            )
            ->getMock();
        $this->audioTrackMapMock = $this
            ->getMockBuilder('Mp3Indexer_Map_AudioTrack')
            ->setMethods(
                array(
                    'setData',
                    'getTarget',
                    'getQuery'
                )
            );
        $this->logMock = $this
            ->getMockBuilder('Mp3Indexer_Log_Client')
            ->getMock();

        $this->object = new Mp3Indexer_Store(
            $this->dispatcherMock,
            $this->logMock,
            $this->mwApiClientMock
        );
    }

    /**
     * test public method and private dependants
     *
     * @covers Mp3Indexer_Store
     *
     * @return void
     */
    public function testCreateOrUpdate()
    {
        $audioTrackMapMock = $this
            ->audioTrackMapMock
            ->getMock();
        
        $audioTrackMapMock
            ->expects($this->exactly(1))
            ->method('setData');
        $audioTrackMapMock
            ->expects($this->exactly(1))
            ->method('getTarget')
            ->will($this->returnValue('TST'));
        $audioTrackMapMock
            ->expects($this->exactly(1))
            ->method('getQuery')
            ->will($this->returnValue(array('Hello World!')));

        $this->eventMock
            ->expects($this->at(0))
            ->method('offsetGet')
            ->with('file')
            ->will($this->returnValue('testbase/testfile'));
        $this->eventMock
            ->expects($this->at(1))
            ->method('offsetGet')
            ->with('data')
            ->will($this->returnValue(array(new stdClass)));
        
        $this->object->addMap(
            $audioTrackMapMock
        );

        $this->assertTrue(
            $this->object->createOrUpdate(
                $this->eventMock
            )
        );
    }

    /**
     * test public method and private dependants
     *
     * @covers Mp3Indexer_Store::createOrUpdate
     *
     * @return void
     */
    public function testCreateOrUpdateMissingFileException()
    {
        $this->dispatcherMock
            ->expects($this->exactly(0))
            ->method('notify');
        
        $this->eventMock
            ->expects($this->at(0))
            ->method('offsetGet')
            ->with('file')
            ->will($this->returnValue(null));
        
        $this->assertFalse(
            $this->object->createOrUpdate(
                $this->eventMock
            ),
            "no file given"
        );

    }
    /**
     * test public method and private dependants
     *
     * @covers Mp3Indexer_Store::createOrUpdate
     *
     * @return void
     */
    public function testCreateOrUpdateMissingDataException()
    {
        $this->dispatcherMock
            ->expects($this->exactly(0))
            ->method('notify');
        
        $this->eventMock
            ->expects($this->at(0))
            ->method('offsetGet')
            ->with('file')
            ->will($this->returnValue('testdir/testfile'));
        $this->eventMock
            ->expects($this->at(1))
            ->method('offsetGet')
            ->with('data')
            ->will($this->returnValue(array()));
        
        $this->assertFalse(
            $this->object->createOrUpdate(
                $this->eventMock
            ),
            "no data in event"
        );
    }
}
