<?php 

namespace App\Services\SimCo;


use App\Traits\ConsumeExternalServices;
use Illuminate\Support\Facades\Log;

class SimCoPortalApiService
{
    use ConsumeExternalServices;

    protected $baseUri;
    

    public function __construct()
    {        
        $this->baseUri = config('services.simco.base_uri');

    }

    public function updateOrderStatus($orderId, $status) {
    
        $serviceMethod = '/v3/orders/' . $orderId;       
        $data = json_encode(['status' => $status]);

        //return $this->makeRequest('PUT', $serviceMethod, [], $data, [], false);

        $url = $this->baseUri.$serviceMethod;
        
        $consumerKey = config('services.simco.consumer_key');
        $consumerSecret = config('services.simco.consumer_secret');
        $auth = base64_encode($consumerKey . ':' . $consumerSecret);
        
        //'Authorization: Basic Y2tfNGJlNzQwODA2MzhlMDBmMGE3OTliMWQzMTFjYmVjNGNhYjNlZWMyZjpjc19jNzc3OGFjYjViNjRlZGU5OTMzNTY3NGFlM2VlZGYwNjk4NTE1ZWNh'

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PUT',
        CURLOPT_POSTFIELDS =>$data,
        CURLOPT_HTTPHEADER => array(
             'Content-Type: application/json',
             'Authorization: Basic '.$auth,
        ),
        ));
            
        $response = curl_exec($curl);

        curl_close($curl);
    
        return $response;
    }


    function getOrderById($orderId) {
        

        $serviceMethod = '/v3/orders/' . $orderId; 
        $url = $this->baseUri.$serviceMethod;

        $consumerKey = config('services.simco.consumer_key');
        $consumerSecret = config('services.simco.consumer_secret');
        $auth = base64_encode($consumerKey . ':' . $consumerSecret);

        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth,
        ];
    
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
             'Content-Type: application/json',
             'Authorization: Basic '.$auth,
        ),
        ));
    
        $response = curl_exec($curl);

        curl_close($curl);
    
        return $response;
    }



    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $consumerKey = config('services.simco.consumer_key');
        $consumerSecret = config('services.simco.consumer_secret');
        $auth = base64_encode($consumerKey . ':' . $consumerSecret);

        $headers['Authorization'] = 'Basic ' . $auth;
        $headers['Content-Type'] = 'application/json';

        Log::debug("headers: " . json_encode($headers));
       
    }





}