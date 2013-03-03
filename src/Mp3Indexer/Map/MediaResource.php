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
 class Mp3Indexer_Map_MediaResource extends Mp3Indexer_Map_SemanticMediawiki {

     const MW_FORM = 'MediaResource';
     
     private $_templateMap = array(
             self::ID3_ALBUM  => 'MediaResource[Locator]',
     );
     
     public function getFile()
     {
         return parent::getFile();
     }

     public function getElements()
     {
         return array();
     }
     
     public function getQuery($form = self::MW_FORM)
     {
         return $this->_getQuery($this->_templateMap, self::MW_FORM);
     }
 }