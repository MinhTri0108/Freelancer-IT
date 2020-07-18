<?php

namespace App;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPalClient
{

    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }

    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public static function environment()
    {
        $clientId = getenv('CLIENT_ID') ?: "<<PAYPAL-CLIENT-ID>>";
        $clientSecret = getenv('CLIENT_SECRET') ?: "<<PAYPAL-CLIENT-SECRET>>";
        // $clientId = 'Ad1hNsyOFDqA_Ti-uUej-eIQ4qysaUF9eOMWHYbYO2NutI8pK6eBXeKycfaWW6xR43GZXjG2XcL3o7V-';
        // $clientSecret = 'EPn2-BxDj46eTOAna3mssOJGfOkUgXu-ad8TluMxXYTkaZgQDPL5MZpeAxDFxafYY979ui1E1Ix3v6p6';
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}
