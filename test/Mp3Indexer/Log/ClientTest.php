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
class Mp3Indexer_Log_ClientTest extends PHPUnit_Framework_TestCase
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
        $this->stdoutMock = $this->getMock('Mp3Indexer_Log_Stdout');
        $this->stdoutMock
            ->expects($this->once())
            ->method('logString')
            ->with("log message");
        
        $this->object = new Mp3Indexer_Log_Client();
    }

    /**
     * check for log calls
     *
     * @return void
     */
    public function testLog()
    {
        $this->object->registerLog($this->stdoutMock);
        $this->object->log("log message");
    }

    /**
     * check for info calls
     *
     * @return void
     */
    public function testInfo()
    {
        $this->object->registerLog($this->stdoutMock);
        $this->object->info("nolog message");
        $this->object->setVerbose();
        $this->object->info("log message");
    }

    /**
     * check for debug calls
     *
     * @return void
     */
    public function testDebug()
    {
        $this->object->registerLog($this->stdoutMock);
        $this->object->debug("nolog message");
        $this->object->setVerbose();
        $this->object->setVerbose();
        $this->object->debug("log message");
    }
}
