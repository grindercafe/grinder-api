<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getToken()
    {
        $client = new Client();

        $response = $client->request(
            'POST',
            'https://restpilot.paylink.sa/api/auth',
            [
                'body' => '{
                "apiId":"APP_ID_1123453311","secretKey":"0662abb5-13c7-38ab-cd12-236e58f43766"
            }',
                'headers' => [
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
            "callBackUrl" => "http://localhost:8000/api/payment_result",
            // "clientEmail" => "myclient@email.com",
            "clientMobile" => $request->phone_number,
            "clientName" => $request->name,
            "orderNumber" => $request->name . Str::uuid()
        ];

        $client = new \GuzzleHttp\Client();
        $token = $this->getToken();

        $response = $client->request('POST', 'https://restpilot.paylink.sa/api/addInvoice', [
            'body' => json_encode($body),
            // 'body' => '{
            //     "amount":200,
            //     "callBackUrl":"https://www.example.com",
            //     "clientEmail":"myclient@email.com",
            //     "clientMobile":"0509200900",
            //     "clientName":"Zaid Matooq","orderNumber":"MERCHANT-ANY-UNIQUE-ORDER-NUMBER-123123123"
            // }',
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

        $response = $client->request('GET', 'https://restpilot.paylink.sa/api/getInvoice/' . $request->trans, [
            'headers' => [
                'Accept' => 'application/json;charset=UTF-8',
                'Authorization' => $request->token,
            ],
        ]);

        return json_decode($response->getBody());
    }

    public function payment_result(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $token = $this->getToken();

        $response = $client->request('GET', 'https://restpilot.paylink.sa/api/getInvoice/' . $request->transactionNo, [
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
