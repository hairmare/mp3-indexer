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
    public function setArtistsFromXml($xmlData)
    {
        
    }
    
    public function setArtists($data)
    {
        
    }
    
    public function getArtists()
    {
    
    }
    
    /**
     * 
     * @param String $name Artist name
     *
     * @return String|Boolean
     */
    public function getArtist($name)
    {
        
    }

    /**
     * return all mediaresource mapping for path segemments.
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
            $base .= $dir.'/';
            $elements[substr($namespace.$base, 0, -1)] = array(
                self::MW_FORM.'[Locator]=' => substr($base, 0, -1)
            );
        }
        return $elements;
    }
}