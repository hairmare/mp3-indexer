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

require_once __DIR__.'/../../src/Mp3Indexer/Scanner.php';

/**
 * Test class for Mp3Indexer_Scanner.
 * Generated by PHPUnit on 2012-05-22 at 19:21:12.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_ScannerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_Scanner
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
        $this->iteratorMock = new RecursiveArrayIterator(
            new RecursiveDirectoryIterator(
                __DIR__.'/../fixtures/testDir'
            )
        );

        $this->dispatcherMock = $this
            ->getMockBuilder('sfEventDispatcher')
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventMock = $this
            ->getMockBuilder('sfEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new Mp3Indexer_Scanner(
            $this->iteratorMock,
            $this->dispatcherMock,
            $this->eventMock
        );
    }

    /**
     * test constructor
     *
     * @covers Mp3Indexer_Scanner::__construct
     *
     * @return void
     */
    public function testConstructor()
    {
        new Mp3Indexer_Scanner(
            $this->iteratorMock,
            $this->dispatcherMock,
            $this->eventMock
        );
    }

    /**
     * only checks for empty recursion as of now
     *
     * @covers Mp3Indexer_Scanner::scan 
     *
     * @return void
     */
    public function testScan()
    {
        $this->object->scan();
    }
}
