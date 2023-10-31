<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function createpaypal()
    {
        return view('paypal_view');
    }

    public function processPaypal(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('processSuccess'),
                "faild_url" => route('processFaild'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "100.00",
                    ]
                ]
            ]
        ]);

        if(isset($response['id']) && $response['id'] != null) {
            foreach($response['links'] as $links){
                if($links['rel'] == 'approve'){
                    return redirect()->away($links['href']);
                }
            }

            return redirect()->route('createpaypal')->with('error', 'Something went wrong.');
        } else {
            return redirect()->route('createpaypal')->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function processSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if(isset($response['status']) && $response['status'] == 'COMPLETED'){
            return redirect()->route('createpaypal')->with('success', 'Transaction Complete.');
        }
        else{
            return redirect()->route('createpaypal')->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function processFaild(Request $request)
    {
        return redirect()->route('createpaypal')->with('error', $response['message'] ?? 'You hace canceled the transaction.');
    }
}
