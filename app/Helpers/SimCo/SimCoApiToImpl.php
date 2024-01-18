<?php
namespace App\Helpers\SimCo;

use Exception;

class SimCoApiToImpl {


    function getOrderById($orderId) {
        
        $url = "https://www.sim-co.net/wp-json/wc/v3/orders/" . $orderId;


        $consumerKey = env('SIMCO_CONSUMER_KEY');
        $consumerSecret = env('SIMCO_MCONSUMER_SECRET');
        $auth = base64_encode($consumerKey . ':' . $consumerSecret);
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth,
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
    
        curl_close($ch);
    
        return $response;
    }


    


    function updateOrderStatus($orderId, $status) {
        
        $url = "https://www.sim-co.net/wp-json/wc/v3/orders/" . $orderId;
        $data = json_encode(['status' => $status]);

        $consumerKey = env('SIMCO_CONSUMER_KEY');
        $consumerSecret = env('SIMCO_MCONSUMER_SECRET');
        $auth = base64_encode($consumerKey . ':' . $consumerSecret);
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth,
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
        $response = curl_exec($ch);
    
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
    
        curl_close($ch);
    
        return $response;
    }


    
}
?>