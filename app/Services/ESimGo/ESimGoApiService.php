<?php

namespace App\Services\ESimGo;

use GuzzleHttp\Client;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Psr7\Request;
use App\Traits\ConsumeExternalServices;

class ESimGoApiService
{

    use ConsumeExternalServices;
    
    protected $baseUri;
    protected $client;
    
    //https://help.esim-go.com/hc/en-gb/articles/11192513354257
    public function __construct($client)
    {
        $this->baseUri = config('services.esimgo.base_uri');
        $this->client = $client;

    }

    public function createOrderService($orderData)
    {

        //  https://docs.esim-go.com/api/#post-/orders
        $serviceMethod = '/v2.3/orders';

        return $this->makeRequest('POST',$serviceMethod,[],$orderData, [], true);
           
    }


    public function assignmentsQR($reference){
    
        $serviceMethod = '/v2.3/esims/assignments/'.$reference;
        $headers = [
            'Accept' => 'application/json',
        ];
        return $this->makeRequest('GET',$serviceMethod,[],[], $headers);
    
    }
    public function updateSimDetails($iccid,$customerRef)
    {
        //  https://docs.esim-go.com/api/#put-/esims
        $serviceMethod = '/v2.3/esims';
        $formdata = [
            "iccid" => $iccid,
            "customerRef" => $customerRef,
        ];

        return $this->makeRequest('PUT',$serviceMethod,[],$formdata, [], true);
           
    }


    private function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $apiToken =env('ESIM_GO_'.$this->client);
        $headers['X-API-Key'] = $apiToken;
        $headers['Content-Type'] = 'application/json';
        return $headers;        
    }

    private function decodeResponse($response)
    {
        return json_decode($response , true);
    }


}