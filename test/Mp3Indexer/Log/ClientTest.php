<?php
/**
 * test log client
 *
 * PHP Version 5
 *
 * @category  Test
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

require_once __DIR__.'/../../../src/Mp3Indexer/Log/Client.php';

/**
 * test log client
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Log_StdoutTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_Log_Client
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
        $this->dispatcherMock = $this->getMock('sfEventDispatcher');

        $this->eventMock = $this->getMockBuilder('sfEvent')
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new Mp3Indexer_Log_Client(
            $this->dispatcherMock
        );
    }

    /**
     * check for log calls
     *
     * @covers Mp3Indexer_Log_Client::log
     *
     * @return void
     */
    public function testLog()
    {
        $this->dispatcherMock
            ->expects($this->once())
            ->method('logEvent');
        
        $this->object->log("log message");
    }

    /**
     * check for info calls
     *
     * @covers Mp3Indexer_Log_Client::info
     *
     * @return void
     */
    public function testInfo()
    {
        $this->dispatcherMock
            ->expects($this->once())
            ->method('logEvent');
    
        $this->object->info("nolog message");
        $this->object->setVerbose();
        $this->object->info("log message");
    }

    /**
     * check for debug calls
     *
     * @covers Mp3Indexer_Log_Client::debug
     *
     * @return void
     */
    public function testDebug()
    {
        $this->dispatcherMock
            ->expects($this->once())
            ->method('logEvent');
    
        $this->object->debug("nolog message");
        $this->object->setVerbose();
        $this->object->setVerbose();
        $this->object->debug("log message");
    }
}