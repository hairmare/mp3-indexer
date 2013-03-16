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
        $artistNs = $namespaces[self::NS_ARTIST];
        
        foreach ($xmlData->query->results->children($artistNs) AS $result) {
            $attributes = $result->attributes();
            
            $this->_artists[(string) $attributes['fulltext']] 
                = (string) $attributes['fullurl'];
        }
    }
    
    /**
     * set artists by calling an swm query
     * 
     * @param Mp3Indexer_MwApiClient $client api client to use
     * @param String                 $query  query string
     * 
     * @return void
     */
    public function setArtistsFromSmw(
        Mp3Indexer_MwApiClient $client, 
        $query = '[[Category:Artist]]'
    ) {
        $this->setArtistsFromXml(
            $client->ask($query)
        );
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
     * get artisturi from internal artist list
     * 
     * @param String $name Artist name
     *
     * @return String|Boolean
     */
    public function getArtistUri($name)
    {
        $name = self::NS_ARTIST.':'.$name;
        $artist = false;
        if (array_key_exists($name, $this->_artists)) {
            $artist = $this->_artists[$name];
        } else {
            $artist = dirname(reset($this->_artists)).'/'.$name;
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
        $artist = $this->getString(self::ID3_ARTIST);
        
        $base = '';
        foreach (explode('/', dirname($this->getFile())) AS $dir) {
            $base .= $dir.'/';
            if ($artist == $dir) {
                $artistUri = $this->getArtistUri($dir);
            
                return array(
                    $this->getNamespace().substr($base, 0, -1) => array(
                        'MediaResource[Locator]=' => substr($base, 0, -1),
                        'Agent[IsDefinedBy]=' => $artistUri
                    ),
                    $this->getNamespace(self::NS_ARTIST).$artist => array(
                        'Agent[Name]=' => $artist
                    )
                );
            }
        }
        return array();
    }
}