<?php 

namespace App\Services\MercadoLibre;

use App\Helpers\MercadoLibre\CallTokenSesion;
use App\Traits\ConsumeExternalServices;
use Illuminate\Support\Facades\Log;

class MLApiServices
{
    use ConsumeExternalServices;

    protected $baseUri;
    protected $id_mercadolibre;

    public function __construct($id_mercadolibre)
    {        
        $this->baseUri = config('services.mercadolibre.base_uri');
        $this->id_mercadolibre = $id_mercadolibre;
    }


    public function getOrderDetailsCall($resourse)
    {
        $order = $this->makeRequest('GET', $resourse);
        $order['order_id'] = $order['id'];

        unset($order['id']);

        return  $order;
    }

    public function sendMessage($orderId,$sellerId,$buyerId,$message){
        
        $serviceMethod = '/messages/packs/'.$orderId.'/sellers/'.$sellerId;
        Log::debug("serviceMethod: " . $serviceMethod);

        $queryParams = [
            'tag' => 'post_sale',
        ];
        Log::debug("queryParams: " . json_encode($queryParams));

        $formParams = '{
            "from":{"user_id":"'.$sellerId.'"},
            "to":{"user_id":"'.$buyerId.'"},
            "text":'.json_encode($message).'
        }';
        Log::debug("formParams: " . $formParams);
        $formParamsDecode = json_decode($formParams);
        
        return $this->makeRequest('POST', $serviceMethod, $queryParams, $formParamsDecode, [], true);
    }



    private function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $accessToken = $this->resolveAccessToken();
        $headers['Authorization'] = $accessToken;
    }
    
    private function decodeResponse($response)
    {
        return json_decode($response , true);
    }

    private function checkIfErrorResponse($response)
    {
        
    }

    private function resolveAccessToken()
    {
        $callToken = new CallTokenSesion();
        return 'Bearer ' . $callToken->getToken($this->id_mercadolibre);
    }


    private function resolveAccessTokenTEST()
    {
        $credentials = base64_encode(config('services.mercadolibre.client_id') . ':' . config('services.mercadolibre.client_secret'));

        $response = $this->makeRequest('POST', '/oauth/token', [], [
            'grant_type' => 'client_credentials',
            'scope' => 'offline_access read write',
        ], [
            'Authorization' => 'Basic ' . $credentials,
        ], true);

        $body = json_decode($response);

        return $body->access_token;
    }

}