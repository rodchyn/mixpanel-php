<?php

/*
If you wish to do metric logging from your backend, the best method of doing this is 
to do it in a non-blocking way. This will let your pages continue to execute at
about the same speed  while logging metric data in the background. Please note: If 
you're on a shared host, you may be limited in logging metric data with a background 
process.

Feel free to modify this code to your own environments liking
*/
class MetricsTracker {
    public $token;
    public $host = 'http://api.mixpanel.com/';
    public function __construct($token_string) {
        $this->token = $token_string;
    }
    function track($event, $properties=array()) {
        $params = array(
            'event' => $event,
            'properties' => $properties
            );

        if (!isset($params['properties']['token'])){
            $params['properties']['token'] = $this->token;
        }
        $url = $this->host . 'track/?data=' . base64_encode(json_encode($params));
        //you still need to run as a background process
        exec("curl '" . $url . "' >/dev/null 2>&1 &"); 
    }
}

// Example usage:
// $metrics = new MetricsTracker("YOUR_TOKEN");
// $metrics->track('purchase', 
//                    array('item'=>'candy', 'type'=>'snack', 'ip'=>'123.123.123.123'));
// You MUST include a distinct_id OR an IP address to track funnels from the backend