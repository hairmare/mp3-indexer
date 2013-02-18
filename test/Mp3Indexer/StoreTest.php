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
            ->getMock();
        $this->textFrameMock = $this
            ->getMockBuilder('Zend_Media_Id3_TextFrame')
            ->setMethods(
                array(
                    'getIdentifier',
                    'getTexts'
                )
            )
            ->getMock();
        $this->audioTrackMapMock = $this
            ->getMockBuilder('Mp3Indexer_Map_AudioTrack')
            ->setMethods(
                array(
                    'setData',
                    'getTarget',
                    'setQuery'
                )
            );

        $this->object = new Mp3Indexer_Store(
            $this->dispatcherMock,
            $this->eventMock,
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
        $this->textFrameMock
            ->expects($this->exactly(2))
            ->method('getIdentifier')
            ->will($this->returnValue('TST'));
        $this->textFrameMock
            ->expects($this->exactly(2))
            ->method('getTexts')
            ->will($this->returnValue(array('Hello World!')));

        $event = clone $this->eventMock;
        $event['file'] = 'testbase/testfile';
        $event['data'] = array(
            $this->textFrameMock
        );

        $this->object->addMap($this->audioTrackMapMock);

        $this->assertTrue(
            $this->object->createOrUpdate(
                $event
            )
        );

        $this->assertTrue(
            $this->object->createOrUpdate(
                $event
            )
        );

        $rowCount = $this
            ->getConnection()
            ->getRowCount('audioFile');
        $this->assertEquals(1, $rowCount);
    }

    /**
     * test public method and private dependants
     *
     * @covers Mp3Indexer_Store::createOrUpdate
     *
     * @todo Implement testCreateOrUpdate().
     *
     * @return void
     */
    public function testCreateOrUpdateException()
    {
        $event = clone $this->eventMock;
        $event['data'] = array();

        $this->dispatcherMock
            ->expects($this->exactly(2))
            ->method('notify');

        $event['file'] = null;
        $this->assertFalse(
            $this->object->createOrUpdate(
                $event
            ),
            "no file given"
        );

        $event['file'] = 'base/file';
        $this->assertFalse(
            $this->object->createOrUpdate(
                $event
            ),
            "no data in event"
        );
    }
}
