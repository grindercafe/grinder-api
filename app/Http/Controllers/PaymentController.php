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
            'apiId' => env('PAYMENT_API_ID'),
            'secretKey' => env('PAYMENT_SECRET_KEY')
        ];

        $response = $client->request(
            'POST',
            env('PAYMENT_LINK') . '/api/auth',
            [
                'body' => json_encode($body),
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
            "callBackUrl" => 'https://api.grindercafe.net/api/result',
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
        $booking = $payment->booking;

        if ($result->orderStatus === 'Paid') {
            $payment->update(['status' => 'paid']);

            $url = 'https://grindercafe.net/bookings/' . $booking->uuid . 
            '?token=' . $booking->token; 
    
            return redirect()->away($url);
        } 
        
        else if($result->orderStatus === 'Cancelled') {
            $payment->update(['status' => 'cancelled']);
            $booking = $payment->booking;
            $booking->tables()->detach();
        } 
        
        else if($result->orderStatus === 'Declined') {
            $payment->update(['status' => 'declined']);
            $booking = $payment->booking;
            $booking->tables()->detach();
        }

        return redirect()->away('https://grindercafe.net/');
    }

    public function updatePaymentStatus()
    {
        $payments = Payment::where('status', 'pending')
        ->where('created_at', '<=', now()->subMinutes(10))
        ->get();

        foreach ($payments as $payment) {
            $payment->update(['status'=> 'timeout']);
            $payment->booking->tables()->detach();
        }

        return response()->json([
            'success'=> true,
            'message'=> 'update payment status and detach tables booking successfully'
        ]);
    }

    // public function getTestToken()
    // {
    //     $client = new Client();

    //     $body = [
    //         'apiId' => 'APP_ID_1123453311',
    //         'secretKey' => '0662abb5-13c7-38ab-cd12-236e58f43766'
    //     ];

    //     $response = $client->request(
    //         'POST',
    //         'https://restpilot.paylink.sa/api/auth',
    //         [
    //             'body' => json_encode($body),
    //             'headers' => [
    //                 'Accept' => '*/*',
    //                 'Content-Type' => 'application/json',
    //             ],
    //         ]
    //     );

    //     $token = $response->getBody();

    //     return json_decode($token)->id_token;
    // }

    // public function createTestInvoice(Request $request)
    // {
    //     $body = [
    //         'amount' => $request->amount,
    //         "callBackUrl" => 'https://api.grindercafe.net/api/test_result',
    //         "clientMobile" => $request->phone_number,
    //         "clientName" => $request->name,
    //         "orderNumber" => $request->name . Str::uuid()
    //     ];

    //     $client = new \GuzzleHttp\Client();
    //     $token = $this->getTestToken();

    //     $response = $client->request('POST', 'https://restpilot.paylink.sa/api/addInvoice', [
    //         'body' => json_encode($body),
    //         'headers' => [
    //             'Accept' => 'application/json;charset=UTF-8',
    //             'Authorization' => 'Bearer ' . $token,
    //             'Content-Type' => 'application/json',
    //         ],
    //     ]);

    //     return json_decode($response->getBody());
    // }

    // public function testResult(Request $request)
    // {
    //     $client = new \GuzzleHttp\Client();

    //     $token = $this->getTestToken();

    //     $response = $client->request('GET', 'https://restpilot.paylink.sa/api/getInvoice/' . $request->transactionNo, [
    //         'headers' => [
    //             'Accept' => 'application/json;charset=UTF-8',
    //             'Authorization' => $token,
    //         ],
    //     ]);

    //     $result = json_decode($response->getBody());

    //     $payment = Payment::where('transactionNo', $result->transactionNo)->first();
    //     $booking = $payment->booking;

    //     if ($result->orderStatus === 'Paid') {
    //         $payment->update(['status' => 'paid']);

    //         $url = 'https://grindercafe.net/bookings/' . $booking->uuid . 
    //         '?token=' . $booking->token; 
    
    //         return redirect()->away($url);
    //     } 
        
    //     else if($result->orderStatus === 'Cancelled') {
    //         $payment->update(['status' => 'cancelled']);
    //         $booking = $payment->booking;
    //         $booking->tables()->detach();
    //     } 
        
    //     else if($result->orderStatus === 'Declined') {
    //         $payment->update(['status' => 'declined']);
    //         $booking = $payment->booking;
    //         $booking->tables()->detach();
    //     }

    //     return redirect()->away('https://grindercafe.net/');

    // }
}
