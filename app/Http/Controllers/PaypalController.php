<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalController extends Controller
{
    private $apiContext;
    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(config('paypal.client_id'),config('paypal.secret'))
        );
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    public function simplePay(Type $var = null)
    {
        # code...
    }
}
