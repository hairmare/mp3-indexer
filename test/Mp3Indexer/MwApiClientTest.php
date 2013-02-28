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

require_once __DIR__.'/../../src/Mp3Indexer/MwApiClient.php';

/**
 * Test class for Mp3Indexer_MwApiClient.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_MwApiClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_MwApiClient
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
        $this->logMock = $this
            ->getMockBuilder('Mp3Indexer_Log_Client')
            ->getMock();
        $this->curlMock = $this
            ->getMockBuilder('Mp3Indexer_Curl')
            ->disableOriginalConstructor()
            ->getMock();
    
        $this->object = new Mp3Indexer_MwApiClient(
            'http://example.com/wiki/api.php',
            $this->curlMock,
            $this->logMock
        );
    }
    
    /**
     * test login method
     * 
     * @return void
     */
    public function testLogin()
    {
        $this->logMock
            ->expects($this->atLeastOnce())
            ->method('log');
        $loginData = '<login>
            <login>
                <cookieprefix>Wiki</cookieprefix>
                <sessionid>123412341234</sessionid>
                <token>asdfasdfasdf</token>
                <lgusername>Testuser</lgusername>
                <lguserid>1</lguserid>
                <lgtoken>qwerqwerqwer</lgtoken>
            </login>
        </login>';
        
        $this->curlMock
            ->expects($this->atLeastOnce())
            ->method('exec')
            ->will($this->returnValue($loginData));
        
        $this->object->login('testuser', 'testpass');
    }
    
    /**
     * simple incomplete test for sfautoedit
     * 
     * @return void
     */
    public function testSfautoedit()
    {
        $this->logMock
            ->expects($this->atLeastOnce())
            ->method('log');
        $this->curlMock
            ->expects($this->atLeastOnce())
            ->method('exec')
            ->will($this->returnValue('<data>something</data>'));
        $this->object->sfautoedit('TestFrom', 'TestTarget', array());
    }
}