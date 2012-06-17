Mixpanel PHP SDK
============

Library include two classes 

[Mixpanel](https://mixpanel.com/docs/api-documentation/data-export-api) - for data requests.

[MetricsTracker](https://mixpanel.com/docs/integration-libraries/php) - to track program events

Example usage
-------------

MetricsTracker

    $metrics = new MetricsTracker("YOUR_TOKEN");
    $metrics->track('purchase', array('item'=>'candy', 'type'=>'snack', 'ip'=>'123.123.123.123'));

Mixpanel

    $api_key = 'your key';
    $api_secret = 'your secret';
    
    $mp = new Mixpanel($api_key, $api_secret);
    $data = $mp->request(array('events', 'properties'), array(
       'event' => 'pages',
       'name' => 'page',
       'type' => 'unique',
       'unit' => 'day',
       'interval' => '20',
       'limit' => '20'
    ));
     
    var_dump($data);