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
require_once __DIR__.'/../../../src/Mp3Indexer/Map/Artist.php';

/**
 * Test class for Mp3Indexer_Map_Artist.
 *
 * @category Test
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Map_ArtistTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mp3Indexer_Map_Artist
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
        $this->artistsXml = simplexml_load_string(
            '<?xml version="1.0"?>
            <api xmlns:Artist="https://example.com/wiki/Category:Artist">
              <query>
                <printrequests>
                  <printrequest label="" typeid="_wpg" mode="2" />
                </printrequests>
                <results>
                  <Artist:The_Hives fulltext="Artist:The Hives" 
                    fullurl="https://example.com/wiki/Artist:The_Hives">
                      <printouts />
                  </Artist:The_Hives>
                </results>
              </query>
            </api>'    
        );
        $this->artists = array(
            'Artist:The Hives' => 'https://example.com/wiki/Artist:The_Hives'    
        );
        
        $this->object = new Mp3Indexer_Map_Artist;
    }
    
    /**
     * test setting/getting of artists
     * 
     * @return void
     */
    public function testSetArtists()
    {
        $this->object->setArtistsFromXml($this->artistsXml);
        $this->assertEquals(
            $this->artists,
            $this->object->getArtists()
        );
    }
    
    /**
     * test checking single artist
     * 
     * @return void
     */
    public function testGetArtist()
    {
        $this->object->setArtists($this->artists);
        $this->assertEquals(
            'https://example.com/wiki/Artist:The_Hives',
            $this->object->getArtist('The Hives')
        );
        $this->assertFalse(
            $this->object->getArtist('Not The Hives')
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
            'file' => 'testdir/The Hives/testfile'
        );
        $data[] = $this->textFrameMock;
        $this->object->setData($data);
        $this->object->setNamespace('Music');
        $this->object->setArtists($this->artists);
        
        $uri = 'https://example.com/wiki/Artist:The_Hives';
        $this->assertEquals(
            array(
                'Music:testdir/The Hives' => array(
                    'MediaResource[Locator]=' => 'testdir/The Hives',
                    'Agent[IsDefinedBy]=' => $uri
                )    
            ),
            $this->object->getElements()
        );
    }
    /**
     * test getElements without match
     *
     * @return void
     */
    public function testGetElements()
    {
        $data = array(
            'file' => 'testdir/Not The Hives/testfile'
        );
        $data[] = $this->textFrameMock;
        $this->object->setData($data);
        $this->object->setNamespace('Music');
        $this->object->setArtists($this->artists);
    
        $this->assertEquals(
            array(),
            $this->object->getElements()
        );
    }
}