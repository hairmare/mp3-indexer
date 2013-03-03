<?php
/**
 * map data to semantic mediawiki MediaResource template
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
 * map MediaResource data
 *
 * @category Map
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_Map_Artist extends Mp3Indexer_Map_SemanticMediawiki
{
    const ARTIST_NS_PREFIX = 'Artist';
    private $_artists = array();
    
    /**
     * load apis from an smw api xml resultset
     * 
     * @param SimpleXmlElement $xmlData parsed xml tree
     * 
     * @return void
     */
    public function setArtistsFromXml($xmlData)
    {
        $namespaces = $xmlData->getDocNamespaces();
        $artistNs = $namespaces[self::ARTIST_NS_PREFIX];
        
        foreach ($xmlData->query->results->children($artistNs) AS $result) {
            $attributes = $result->attributes();
            
            $this->_artists[(string) $attributes['fulltext']] = 
                (string) $attributes['fullurl'];
        }
    }
    
    /**
     * Set array of artist - uri mapping
     * 
     * @param Array $data given array
     * 
     * @return void
     */
    public function setArtists($data)
    {
        $this->_artists = $data;
    }
    
    /**
     * get array of artist - uri mappings
     * 
     * @return Array
     */
    public function getArtists()
    {
        return $this->_artists;
    }
    
    /**
     * 
     * @param String $name Artist name
     *
     * @return String|Boolean
     */
    public function getArtist($name)
    {
        $name = self::ARTIST_NS_PREFIX.':'.$name;
        $artist = false;
        if (array_key_exists($name, $this->_artists)) {
            $artist = $this->_artists[$name];
        }
        return $artist;
    }

    /**
     * return agent mapping for first matching segemment.
     * 
     * @see Mp3Indexer_Map_SemanticMediawiki::getElements()
     * 
     * @return Array
     */
    public function getElements()
    {
        $namespace = $this->getNamespace();
        $base = '';
        foreach (explode('/', dirname($this->getFile())) AS $dir) {
            $artistUri = $this->getArtist($dir);
            $base .= $dir.'/';
            
            if ($artistUri !== false) {
                return array(
                    substr($namespace.$base, 0, -1) => array(
                        'MediaResource[Locator]=' => substr($base, 0, -1),
                        'Agent[IsDefinedBy]=' => $artistUri
                    )
                );
            }
        }
        return array();
    }
}