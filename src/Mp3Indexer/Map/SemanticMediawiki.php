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
	
	public abstract function getTarget();
	
	public abstract function getQuery();
	
	public function setData($data) {
		$this->_data = $data;
	}
	
	protected function _getString($tagName) {
		foreach ($this->_data AS $tag) {
			if (is_callable(array($tag,'getIdentifier')) && $tag->getIdentifier() == $tagName) {
				return array_pop($this->_getSimpleValue($tag));
			}
		}
	}

	protected function _getQuery($map, $form = self::MW_FORM)
	{
		$query = array($form.'[Locator]=' => (string) $this->_data['file']);
		foreach ($this->_data AS $tag) {
			if (is_callable(array($tag,'getIdentifier')) && !empty($map[$tag->getIdentifier()])) {
				$query[$map[$tag->getIdentifier()].'='] = $this->_getString($tag->getIdentifier());
			}
		}
		return $query;
	}

	/**
	 * convert and insert found tags
	 *
	 * @param Object  $value  a Zend_Media_* instance
	 *
	 * @return void
	 */
	protected function _getSimpleValue($value)
	{
		if (is_a($value, 'Zend_Media_Id3_TextFrame')) {
			$tagValues = $value->getTexts();
		} else if (is_a($value, 'Zend_Media_Id3_LinkFrame')) {
			$tagValues = array($value->getLink());
		} else if (is_a($value, 'Zend_Media_Id3_Frame_Ufid')) {
			// @todo implement serious loading
			$tagValues = array($value->getOwner());
		} else if (is_a($value, 'Zend_Media_Id3_Frame_Apic')) {
			// @todo implement picture loading
			$tagValues = array($value->getImageType());
		} else if (is_a($value, 'Zend_Media_Id3_Frame_Comm')) {
			// @todo implement serious loading
			$tagValues = array(
					$value->getDescription().' : '.$value->getText()
			);
		} else if (is_a($value, 'Zend_Media_Id3_Frame_Priv')) {
			// @todo do we need all these
			$tagValues = array(
					$value->getOwner().' : '.$value->getData()
			);
		} else {
			$tagValues = array(var_export($value, true));
		}
		return $tagValues;
	}
}