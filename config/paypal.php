<?php
return[
    'client_id' => env('PAYPAL_CLIENT_ID'),
    'secret' => env('PAYPAL_SECRET'),
    'settings' => [
        'http.CURLOPT_CONNECTTIMEOUT' => 1200,
        'mode' => env('PAYPAL_MODE'), //live
        'log.LogEnabled' => true,
        'log.FileName' => storage_path().'/logs/paypal.php',
        'log.LogLevel' => 'FINE',
    ]
]
?>