<?php
/**
 * oo curl function wrapper
 *
 * PHP Version 5
 *
 * @category  Curl
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * expose curl in an oo matter
 * 
 * needed so i have a single place to ignore code coverage
 *
 * @category Curl
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 * 
 * @codeCoverageIgnore
 */
class Mp3Indexer_Curl
{
    /**
     * curl object constructor
     * 
     * @return void
     */
    public function __construct()
    {
        $this->_ch = curl_init();
    }
    
    /**
     * call curl_setopt on instance
     * 
     * @param Integer $option curl_setopt constant
     * @param Mixed   $value  curl_setopt value
     * 
     * @return void
     */
    public function setopt($option, $value)
    {
        curl_setopt($this->_ch, $option, $value);
    }
    
    /**
     * call curl exec on instance
     * 
     * @return Mixed
     */
    public function exec()
    {
        return curl_exec($this->_ch);
    }
}