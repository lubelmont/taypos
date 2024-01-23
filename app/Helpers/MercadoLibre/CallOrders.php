<?php

namespace App\Helpers\MercadoLibre;

use App\Services\MercadoLibre\MercadoLibreApiServices;
use Illuminate\Support\Facades\Log;

class CallOrders
{


    public function getOrderDetails($order)
    {
        //example
        // '_id' => '497fa5d6-2cc1-4c45-905e-3172cf05644e',
        // 'topic' => 'orders_v2',
        // 'resource' => '/orders/2000007002672920',
        // 'user_id' => 1566150988,
        // 'application_id' => 6722168577139405,
        // 'sent' => '2023-12-03T21:29:52.641Z',
        // 'attempts' => 2,
        // 'received' => '2023-12-03T21:28:43.546Z',

        //TODO: check if order exists before get from the service

        $id_mercadolibre = $order["user_id"];
        $resource = $order["resource"];

        $MLTokenSesion = new CallTokenSesion();
        $token = $MLTokenSesion->getToken($id_mercadolibre);

        Log::info("<------------------token------------------>");
        Log::info($token);
        Log::info("<------------------token------------------>");

        $orderDetails = $this->getOrderDetailsCall($resource,$token);
        Log::info("<------------------orderDetails------------------>");
        Log::info($orderDetails);
        Log::info("<------------------orderDetails------------------>");
        return $orderDetails;

    }


    private function getOrderDetailsCall($resourse,$token){

        $curl = curl_init();
    
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadolibre.com'.$resourse,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$token
        ),
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);

        $orderDetails = json_decode($response , true);

        if ($orderDetails === null && json_last_error() !== JSON_ERROR_NONE) {
            return null;
        } 

        return $orderDetails;
    }
      
}
