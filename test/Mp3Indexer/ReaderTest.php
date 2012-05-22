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

require_once __DIR__.'/../../src/Mp3Indexer/Reader.php';

/**
 * Test class for Mp3Indexer_Reader.
 * Generated by PHPUnit on 2012-05-19 at 15:02:44.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_ReaderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_Reader
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

        $eventBuilder = $this->eventBuilder = $this
            ->getMockBuilder('sfEvent')
            ->disableOriginalConstructor();

        $this->linterMock = $eventBuilder->getMock();
        $this->dataMock = $eventBuilder->getMock();
        $this->logMock = $eventBuilder->getMock();
        $this->readerFactoryMock = $this
            ->getMockBuilder('Mp3Indexer_ReaderImplFactory')
            ->getMock();
                                 
        $this->object = new Mp3Indexer_Reader(
            $this->dispatcherMock,
            $this->linterMock,
            $this->dataMock,
            $this->logMock,
            $this->readerFactoryMock
        );
    }

    /**
     * test constructor
     *
     * @covers Mp3Indexer_Reader::__construct
     *
     * @return void
     */
    public function testConstructor()
    {
        new Mp3Indexer_Reader(
            $this->dispatcherMock,
            $this->linterMock,
            $this->dataMock,
            $this->logMock,
            $this->readerFactoryMock
        );
    }

    /**
     * test read method
     *
     * @covers Mp3Indexer_Reader::read
     *
     * @return void
     */
    public function testRead()
    {
        $event = $this->eventBuilder->getMock();
        $event->file = '/tmp/hello/world';

        $this->dispatcherMock
            ->expects($this->once())
            ->method('filter')
            ->with(
                $this->linterMock,
                '/tmp/hello/world'
            );

        $readerMock = $this->getMock('stdClass');

        $this->readerFactoryMock
            ->staticExpects($this->once())
            ->method('getReader')
            ->with('/tmp/hello/world')
            ->will($this->returnValue($readerMock));


        $this->object->read($event);
    }
}
