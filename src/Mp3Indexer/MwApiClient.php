<?php
/**
 * mediawiki api client
 *
 * PHP Version 5
 *
 * @category  Store
 * @package   Mp3Indexer
 * @author    Lucas S. Bickel <hairmare@purplehaze.ch>
 * @copyright 2013 - Alle Rechte vorbehalten
 * @license   GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link      http://github.com/purplehazech/mp3-indexer
 */

/**
 * access mediawiki in a sane fashion
 *
 * @category MwApiClient
 * @package  Mp3Indexer
 * @author   Lucas S. Bickel <hairmare@purplehaze.ch>
 * @license  GPL http://www.gnu.org/licenses/gpl-3.0.txt
 * @link     http://github.com/purplehazech/mp3-indexer
 */
class Mp3Indexer_MwApiClient
{
    private $_sanitationMap = array(
          '[' => '(',
          ']' => ')',
          '#' => '',
          '<' => '(',
          '>' => ')',
          '|' => '-',
          '{' => '(',
          '}' => ')'
    );
    
    private $_header = array();
    
    /**
     * create new api instance
     * 
     * @param String     $apiurl url to mediawikis api.php endpoint
     * @param Mp3Indexer $curl   oo wrapper to curl 
     */
    public function __construct($apiurl, Mp3Indexer_Curl $curl)
    {
        $this->_apiurl = $apiurl;
        $this->_curl = $curl;
        $this->_curl->setopt(CURLOPT_RETURNTRANSFER, 1);
        $this->_curl->setopt(CURLOPT_HEADER, 0);
        $this->_curl->setopt(CURLOPT_POST, 1);
    }
    
    /**
     * login to mediawikis api
     * 
     * @param String $username mediawiki bot username
     * @param String $password mediawiki bot password
     * @param String $domain   mediawiki bot domain (ie for ldap logins)
     * 
     * @return void
     */
    public function login($username, $password, $domain = null)
    {
        $args = array(
                'lgname' => $username, 
                'lgpassword' => $password, 
                'lgdomain' => $domain
        );
        $login = $this->_callApi(
            'login',
            $args
        );
        $prefix = $login->login['cookieprefix'];
        
        $cookie = $prefix.'_session='.$login->login['sessionid'];

        $args['lgtoken'] = $login->login['token'];
        $this->_header = array(
            'Cookie: '.
            $cookie
        );
        $confirm = $this->_callApi(
            'login',
            $args
        );
        
        $username = $prefix.'UserName='.$confirm->login['lgusername'];
        $userid = $prefix.'UserID='.$confirm->login['lguserid'];
        $token = $prefix.'Token='.$confirm->login['lgtoken'];
        
        $this->_header = array(
            'Cookie: '.
            $cookie.'; '.
            $username.'; '.
            $userid.'; '.
            $token
        );
    }
    
    /**
     * call the sfautoedit action from semantic mediawiki
     * 
     * @param String $form   smw form to use
     * @param String $target target page in wiki
     * @param Array  $query  array of query params as per docs
     * 
     * @return void
     */
    public function sfautoedit($form, $target, $query)
    {
        $target = $this->_sanitizeTitle($target);
        return $this->_callApi(
            'sfautoedit',
            array_merge(
                array(
                    'form' => $form,
                    'target' => $target,
                ),
                $query
            )
        );
    }
    
    /**
     * actual calls to api
     * 
     * Call the api using the given params. 
     * 
     * @param String $action mediawiki api action
     * @param Array  $params mediawiki api params
     * @param Array  $format mediawiki api format
     * 
     * @return SimpleXMLElement
     */
    private function _callApi($action, $params, $format = 'xml')
    {
        $url = $this->_apiurl.'?action='.$action.'&format='.$format;
        
        $this->_curl->setopt(CURLOPT_POSTFIELDS, $params);
        $this->_curl->setopt(CURLOPT_HTTPHEADER, $this->_header);
        $this->_curl->setopt(CURLOPT_URL, $url);
        
        $return = $this->_curl->exec();
        
        return simplexml_load_string($return);
    }
    
    /**
     * clean up a title according to mediawiki limitiations.
     * 
     * does some creative replacing for some variables
     *
     * @param String $title generated title that might contain insane chars
     * 
     * @return String
     */
    private function _sanitizeTitle($title) 
    {
        return strtr($title, $this->_sanitationMap);
    }
}