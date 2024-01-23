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
        Log::debug("serviceMethod --: " .  $this->baseUri.$serviceMethod);


        $formdata = [
            "status" => $status,
        ];
        $data = json_encode(['status' => $status]);

        Log::debug("formdata: " . json_encode($formdata));
    
        //return $this->makeRequest('PUT', $serviceMethod, [], $data, [], false);

        $url = $this->baseUri.$serviceMethod;
        Log::debug("url: " . $url);
        $consumerKey = config('services.simco.consumer_key');
        $consumerSecret = config('services.simco.consumer_secret');
        $auth = base64_encode($consumerKey . ':' . $consumerSecret);
        Log::debug("auth: " . $auth);

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