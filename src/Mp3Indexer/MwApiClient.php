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
     * @param String $apiurl
     */
    public function __construct($apiurl)
    {
        $this->_apiurl = $apiurl;
        $this->_curl = curl_init();
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_curl, CURLOPT_HEADER, 0);
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
    public function login($username, $password, $domain = NULL)
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
        
        $cookie = $login->login['cookieprefix'].'_session='.$login->login['sessionid'];

        $args['lgtoken'] = $login->login['token'];
        $this->_header = array('Cookie: '.$cookie);
        $confirm = $this->_callApi(
            'login',
            $args
        );
        
        $username = $confirm->login['cookieprefix'].'UserName='.$confirm->login['lgusername'];
        $userid = $confirm->login['cookieprefix'].'UserID='.$confirm->login['lguserid'];
        $token = $confirm->login['cookieprefix'].'Token='.$confirm->login['lgtoken'];
        
        $this->_header = array('Cookie: '.$cookie.'; '.$username.'; '.$userid.'; '.$token);
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
     * @param String $method POST or GET
     * @param Array  $format mediawiki api format
     * 
     * @return SimpleXMLElement
     */
    private function _callApi($action, $params, $post = true, $format = 'xml')
    {
        $url = $this->_apiurl.'?action='.$action.'&format='.$format;
        if ($post) {
             curl_setopt($this->_curl, CURLOPT_POST, $post);
             curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $params);
        } else {
            foreach ($params AS $name => $value) {
                $url .= '&'.$name.'='.$value;
            }
        }
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, $this->_header);
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        $return = curl_exec($this->_curl);
        
        if (!is_string($return)) {
            // @todo build error handling
            var_dump('ret', $return);
            throw new Exception('le fail')
        }
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