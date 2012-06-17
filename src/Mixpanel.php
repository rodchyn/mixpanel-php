<?php

/*
 * PHP library for Mixpanel data API -- http://www.mixpanel.com/
 * Requires PHP 5.2 with JSON
 */

class Mixpanel
{
	private $api_url = 'http://mixpanel.com/api';
	private $version = '2.0';
	private $api_key;
	private $api_secret;
	
	public function __construct($api_key, $api_secret) {
		$this->api_key = $api_key;
		$this->api_secret = $api_secret;
	}
	
	public function request($methods, $params, $format='json') {
		// $end_point is an API end point such as events, properties, funnels, etc.
		// $method is an API method such as general, unique, average, etc.
		// $params is an associative array of parameters.
		// See http://mixpanel.com/api/docs/guides/api/
		
		if (count($params) < 1)
			return false;
		
		if (!isset($params['api_key']))
			$params['api_key'] = $this->api_key;
		
		$params['format'] = $format;
		
		if (!isset($params['expire'])) {
			$current_utc_time = time() - date('Z');
			$params['expire'] = $current_utc_time + 600; // Default 10 minutes
		}
		
		$param_query = '';
		foreach ($params as $param => &$value) {
			if (is_array($value))
				$value = json_encode($value);
			$param_query .= '&' . urlencode($param) . '=' . urlencode($value);
		}
		
		$sig = $this->signature($params);
		
		$uri = '/' . $this->version . '/' . join('/', $methods) . '/';
		$request_url = $uri . '?sig=' . $sig . $param_query;
		
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$this->api_url . $request_url);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$data = curl_exec($curl_handle);
		curl_close($curl_handle);
		
		return json_decode($data, true);
	}
	
	private function signature($params) {
		ksort($params);
        $param_string ='';
		foreach ($params as $param => $value) {
			$param_string .= $param . '=' . $value;
		}
		
		return md5($param_string . $this->api_secret);
	}
}
	
// Example usage
// $api_key = 'your key';
// $api_secret = 'your secret';
// 
// $mp = new Mixpanel($api_key, $api_secret);
// $data = $mp->request(array('events', 'properties'), array(
//     'event' => 'pages',
//     'name' => 'page',
//     'type' => 'unique',
//     'unit' => 'day',
//     'interval' => '20',
//     'limit' => '20'
// ));
// 
// var_dump($data);

?>