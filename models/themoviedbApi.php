<?php

namespace app\models;

use Yii;
use yii\base\Model;

class themoviedbApi extends Model
{
    const POST = 'post';
    const GET = 'get';
    const HEAD = 'head';
        
    const IMAGE_BACKDROP = 'backdrop';
    const IMAGE_POSTER = 'poster';
    const IMAGE_PROFILE = 'profile';
    
    const API_VERSION = '3';
    const API_URL = 'api.themoviedb.org';
    const API_SCHEME = 'http://';
    const API_SCHEME_SSL = 'https://';
    
    const VERSION = '1.5.0';
    
    public $apikey;
    protected $_config;
    protected $_session_id;
    protected $_apischeme;
    protected $_lang;
    
    public function rules()
    {
        return [
            [['apikey'], 'required'],
        ];
    }
    
    public function startApi($apikey = NULL)
    {
        if ($apikey != NULL)
        {
            $this->apikey = $apikey;
        } 
        
        $default_lang = 'en';
        $scheme = themoviedbApi::API_SCHEME;
        
	$this->_apischeme = ($scheme == themoviedbApi::API_SCHEME) ? themoviedbApi::API_SCHEME : themoviedbApi::API_SCHEME_SSL;
	$this->setLang($default_lang);
        
        if ($this->getConfiguration())
        {
            return $this->apikey;
	} 
        
        return FALSE;
    }
    
    public function setLang($lang)
    {
        $this->_lang = $lang;
    }
    
    public function getLang()
    {
	return $this->_lang;
    }
    
    public function getConfiguration()
    {
	$config = $this->_makeCall('configuration');

	if (!empty($config))
	{
            $this->setConfig($config);
	}

	return $config;
    }
    
    public function setConfig($config)
    {
	$this->_config = $config;
    }
    
    private function _makeCall($function, $params = NULL, $session_id = NULL, $method = themoviedbApi::GET)
    {
	$params = ( ! is_array($params)) ? array() : $params;
	$auth_array = array('api_key' => $this->apikey);
        
	if ($session_id !== NULL)
	{
            $auth_array['session_id'] = $session_id;
	}

	$url = $this->_apischeme.themoviedbApi::API_URL.'/'.themoviedbApi::API_VERSION.'/'.$function.'?'.http_build_query($auth_array, '', '&');

	if ($method === themoviedbApi::GET)
	{
            if (isset($params['language']) AND $params['language'] === FALSE)
            {
		unset($params['language']);
            }

            $url .= ( ! empty($params)) ? '&'.http_build_query($params, '', '&') : '';
	}

	$results = '{}';

        $headers = array(
            'Accept: application/json',
        );

	$ch = curl_init();

	if ($method == themoviedbApi::POST)
	{
            $json_string = json_encode($params);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: '.strlen($json_string);
	}
	elseif ($method == themoviedbApi::HEAD)
	{
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
		curl_setopt($ch, CURLOPT_NOBODY, 1);
	}

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec($ch);

	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($response, 0, $header_size);
	$body = substr($response, $header_size);

	$error_number = curl_errno($ch);
	$error_message = curl_error($ch);

	curl_close($ch);

	$results = json_decode($body, TRUE);

	if (strpos($function, 'authentication/token/new') !== FALSE)
	{
            $parsed_headers = $this->_http_parse_headers($header);
            $results['Authentication-Callback'] = $parsed_headers['Authentication-Callback'];
	}

	if ($results !== NULL)
	{
            return $results;
	}
	elseif ($method == themoviedbApi::HEAD)
	{
            return $this->_http_parse_headers($header);
	}
    }
    
    public function getPopularMovies($page = 1, $lang = NULL)
    {
	$params = array(
            'page' => (int) $page,
            'language' => ($lang !== NULL) ? $lang : $this->getLang(),
	);
		
        return $this->_makeCall('movie/popular', $params);
    }
    
    public function getNowPlayingMovies($page = 1, $lang = NULL)
    {
	$params = array(
            'page' => (int) $page,
            'language' => ($lang !== NULL) ? $lang : $this->getLang(),
	);
	
        return $this->_makeCall('movie/now_playing', $params);
    }
}

?>
