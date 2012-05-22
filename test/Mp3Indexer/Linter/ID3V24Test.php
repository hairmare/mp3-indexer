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

require_once __DIR__.'/../../../src/Mp3Indexer/Linter/ID3V24.php';

/**
 * Test class for Mp3Indexer_Linter_ID3V24.
 * Generated by PHPUnit on 2012-05-22 at 19:47:40.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Linter_ID3V24Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_Linter_ID3V24
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
            ->disableOriginalConstructor()
            ->getMock();

        $this->object = new Mp3Indexer_Linter_ID3V24(
            $this->dispatcherMock
        );
    }

    /**
     * test for event registration
     *
     * @covers Mp3Indexer_Linter_ID3V24::__construct
     *
     * @return void
     */
    public function testConstructor()
    {
        $this->dispatcherMock
            ->expects($this->once())
            ->method('connect');

        new Mp3Indexer_Linter_ID3V24(
            $this->dispatcherMock
        );
    }

    /**
     * needs refactoring b4 testing
     * 
     * @todo Implement testLint().
     *
     * @covers Mp3Indexer_Linter_ID3V24::lint
     *
     * @return void
     */
    public function testLint()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>
