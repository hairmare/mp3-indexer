<?php
/**
 * map data to semantic mediawiki templates
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
 * map data
 * 
 * This abstract class contains some basic methods and defines an api.
 *
 * @category Map
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
abstract class Mp3Indexer_Map_SemanticMediawiki
{
    const MW_FORM = '';
    const ID3_TITLE = 'TIT2';
    const ID3_ARTIST = 'TPE1';
    const ID3_ALBUM = 'TALB';
    const ID3_YEAR = 'TYER';
    const ID3_NUMBERPOS = 'TRCK';
    const ID3_GENRE = 'TCON';
    
    const NS_ARTIST = 'Artist';
    const NS_MUSIC = 'Music';
    
    private $_data = array();
    
    private $_namespaces = array(
        self::NS_MUSIC => '',
        self::NS_ARTIST => ''    
    );
    
    /**
     * return multiple mediawiki target with data in one go
     * 
     * @return Array
     */
    public abstract function getElements();
    
    /**
     * return an array of arguments to the sfautoedit api method query param
     * 
     * @param String $form name of form to use
     *
     * @return array
     */
    public function getQuery($form = self::MW_FORM)
    {
        return $this->_getQuery($this->templateMap, $form);
    }
    
    /**
     * inject an array of information frames
     * 
     * @param Array $data contains id3 frames and the mathing file object
     * 
     * @return void
     */
    public function setData($data)
    {
        $this->_data = $data;
    }
    
    /**
     * get file we are looking at
     * 
     * @return SplFileInfo
     */
    protected function getFile()
    {
        return $this->_data['file'];
    }
    
    /**
     * set namespace for getTarget
     * 
     * @param String $namespace      Namespace
     * @param String $namespace_type Type self::NS_<type>
     * 
     * @return void
     */
    final function setNamespace($namespace, $namespace_type = self::NS_MUSIC)
    {
        $this->_namespaces[$namespace_type] = $namespace;
    }
    
    /**
     * get namespace for getTarget
     * 
     * @param String $namespace_type Type self::NS_<type>
     * 
     * @return String
     */
    final function getNamespace($namespace_type = self::NS_MUSIC)
    {
        if ($this->_namespaces[$namespace_type] !== '') {
            $namespace = $this->_namespaces[$namespace_type].':';
        } else {
            $namespace = '';
        }
        return $namespace;
    }
    
    /**
     * gets the first string from an id3 frame
     * 
     * @param String $frameName Id3 frame name
     * 
     * @return String|NULL
     */
    protected function getString($frameName)
    {
        $string = null;
        foreach ($this->_data AS $tag) {
            if ($string !== null) {
                continue;
            }
            if (!is_callable(array($tag, 'getIdentifier'))) {
                continue;
            }
            if ($tag->getIdentifier() == $frameName) {
                $values = $this->_getSimpleValue($tag);
                $string = array_pop($values);
            }
        }
        return $string;
    }

    /**
     * generic query builder used by subclasses
     * 
     * @param Array  $map  map of query templates
     * @param String $form name of smw form to use
     * 
     * @return Array
     * 
     * @todo rework to support file names through templating
     */
    private function _getQuery($map, $form = self::MW_FORM)
    {
        $query = array($form.'[Locator]=' => (string) $this->getFile());
        foreach ($this->_data AS $tag) {
            if (!is_callable(array($tag, 'getIdentifier'))) {
                continue;
            }
            $tagName = $tag->getIdentifier();
            if (!empty($map[$tagName])) { 
                $query[$map[$tagName].'='] = $this->getString($tagName);
            }
        }
        return $query;
    }

    /**
     * convert and insert found tags
     *
     * @param Object $value a Zend_Media_* instance
     *
     * @return Array
     * 
     * re-add listed classes as needed
     * - Zend_Media_Id3_LinkFrame
     * - Zend_Media_Id3_Frame_Ufid
     * - Zend_Media_Id3_Frame_Apic
     * - Zend_Media_Id3_Frame_Comm
     * - Zend_Media_Id3_Frame_Priv
     */
    private function _getSimpleValue($value)
    {
        $tagValues = array();
        if (is_a($value, 'Zend_Media_Id3_TextFrame')) {
            $tagValues = $value->getTexts();
        }
        return $tagValues;
    }
}