<?php
/**
 * map data to semantic mediawiki AudioTrack template
 *
 * PHP Version 5
 *
 * @category  Map
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * map AudioTrack data
 *
 * @category Map
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Map_AudioTrack extends Mp3Indexer_Map_SemanticMediawiki
{
    const MW_FORM = 'AudioTrack';
    
    protected $templateMap = array(
        self::ID3_ALBUM  => 'AudioTrack[IsTrackOf]',
        self::ID3_TITLE  => 'AudioTrack[TrackName]',
        self::ID3_ARTIST => 'AudioTrack[HasCreator]',
        self::ID3_YEAR   => 'AudioTrack[RecordDate]'
    );
    
    /**
     * return name based on data from request
     * 
     * @todo add namespace support
     * 
     * @see Mp3Indexer_Map_SemanticMediawiki::getTarget()
     * 
     * @return String
     */
    public function getTarget()
    {
        return (string) $this->getFile();
    }
    
    /**
     * return query array
     * 
     * @param String $form name of form to use
     * 
     * @see Mp3Indexer_Map_SemanticMediawiki::getQuery()
     * 
     * @return Array
     */
    public function getQuery($form = self::MW_FORM)
    {
        return parent::getQuery($form);
    }
    

    /**
     * return a single mediawiki target with data
     *
     * @return Array
     */
    public function getElements()
    {
        return array($this->getTarget() => $this->getQuery());
    }
}