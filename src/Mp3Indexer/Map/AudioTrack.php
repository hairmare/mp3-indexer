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
    
    private $_templateMap = array(
        self::ID3_ALBUM  => 'AudioTrack[IsTrackOf]',
        self::ID3_TITLE  => 'AudioTrack[TrackName]',
        self::ID3_ARTIST => 'AudioTrack[HasCreator]',
        self::ID3_YEAR   => 'AudioTrack[RecordDate]'
    );
    
    /**
     * return name based on data from request
     * 
     * @todo rewrite this so it uses a sensible chksum (ala shorturl)
     * @todo add namespace support
     * 
     * @see Mp3Indexer_Map_SemanticMediawiki::getTarget()
     * 
     * @return String
     */
    public function getTarget()
    {
        $track = $this->_getString(self::ID3_TITLE);
        $artist = $this->_getString(self::ID3_ARTIST);
        $album = $this->_getString(self::ID3_ALBUM);
        if ($album) {
            $target = $track.' von '.$artist.' auf '.$album.' (Track)';
        } else {
            $target = $track.' von '.$artist.' (Track)';
        }
        return $target;
    }
    
    /**
     * return query array
     * 
     * @see Mp3Indexer_Map_SemanticMediawiki::getQuery()
     * 
     * @return Array
     */
    public function getQuery()
    {
        return parent::getQuery(self::MW_FORM);
    }
}