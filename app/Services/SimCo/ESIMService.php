<?php

namespace App\Services\SimCo;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use GuzzleHttp\Psr7\Request;


class ESIMService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('ESIM_GO_URL'),
            'headers' => [
                'X-API-Key' => env('ESIM_GO_LUBEL'),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function createOrder($orderData)
    {
        try {
            $response = $this->client->post('orders', [
                'json' => $orderData
            ]);
    
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody();
                Log::error("message: " . $errorMessage);
            } else {
                Log::error("message: The esim-go API is not available");
            }
        }
    }

    public function assignmentsQR($reference){

        
        
        try {
            
            $uri = 'esims/assignments/'.$reference;

            $headers = [
                'X-API-Key' => env('ESIM_GO_LUBEL'),
                'Accept' => 'application/json',
            ];

            $client = new Client(['base_uri' => env('ESIM_GO_URL')]);


            $request = new Request('GET', $uri, $headers);
           
            $response = $client->sendAsync($request)->wait();
 
            $responseBody = $response->getBody();
    
            return json_decode($response->getBody(), true);
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody();
                Log::error("message: " . $errorMessage);
            } else {
                Log::error("message: The esim-go API is not available");
            }
        }
    }

    public function applyOrder($sku)
    {
        try {
            $response = $this->client->get('esims/apply', [
                'json' => [
                    "iccid" => "",
                    "name" => $sku,
                    "startTime"=>"",
                    "repeat"=>0
                ]
            ]);
    
            return json_decode($response->getBody(), true);
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody();
                Log::error("message: " . $errorMessage);
            } else {
                Log::error("message: The esim-go API is not available");
            }
        }
    }
}