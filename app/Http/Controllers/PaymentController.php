<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getToken()
    {
        $client = new Client();

        $body = [
            'apiId'=> env('PAYMENT_API_ID'),
            'secretKey'=> env('PAYMENT_SECRET_KEY')
        ];

        $response = $client->request(
            'POST',
            env('PAYMENT_LINK') . '/api/auth',
            [
                'body'=> json_encode($body),
                'headers'=> [
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                ],
            ]
        );

        $token = $response->getBody();

        return json_decode($token)->id_token;
    }

    public function createInvoice(Request $request)
    {
        $body = [
            'amount' => $request->amount,
            "callBackUrl" => env('PAYMENT_CALLBACK_URL'),
            "clientMobile" => $request->phone_number,
            "clientName" => $request->name,
            "orderNumber" => $request->name . Str::uuid()
        ];

        $client = new \GuzzleHttp\Client();
        $token = $this->getToken();

        $response = $client->request('POST', env('PAYMENT_LINK') . '/api/addInvoice', [
            'body' => json_encode($body),
            'headers' => [
                'Accept' => 'application/json;charset=UTF-8',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function getInvoice(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', env('PAYMENT_LINK') . '/api/getInvoice/' . $request->trans, [
            'headers' => [
                'Accept' => 'application/json;charset=UTF-8',
                'Authorization' => $request->token,
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function result(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $token = $this->getToken();

        $response = $client->request('GET', env('PAYMENT_LINK') . '/api/getInvoice/' . $request->transactionNo, [
            'headers' => [
                'Accept' => 'application/json;charset=UTF-8',
                'Authorization' => $token,
            ],
        ]);

        $result = json_decode($response->getBody());

        $payment = Payment::where('transactionNo', $result->transactionNo)->first();

        if($result->orderStatus === 'Paid') {
            $payment->update(['status'=> 'paid']);
        } 

        return redirect('http://localhost:3000/events');
    }
}
