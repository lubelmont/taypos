<?php

namespace App\Helpers\MercadoLibre;

use App\Models\MercadoLibreMessagePosSell;
use App\Models\MercadoLibreUsuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class CallMessages
{

    public function sendMensajeCompleOrder($order)
    {
        $id_mercadolibre = $order["seller_id"];

        $user = MercadoLibreUsuario::where('id_mercadolibre', $id_mercadolibre)->first();


        $messagePosSell = MercadoLibreMessagePosSell::where('user_id', $user->user_id)->first();

        $orderIDCrypted =  Crypt::encrypt($order->id);

        $uri = 'https://www.sim-co.net/ml-orders/?orderId='.$orderIDCrypted ;

        $message = $messagePosSell->message_to_bill;
        $message = str_replace("{URL}", '<a href="'.$uri.'"> Descarga tus codigos </a>', $message);
        $message = str_replace("{DAYS}", $messagePosSell->days_alive, $message);
       

        $MLTokenSesion = new CallTokenSesion();

        $token = $MLTokenSesion->getToken($id_mercadolibre);

        $orderDetails = $this->sendMessageToBuyer($order->id,$order->seller_id,$order->buyer_id,$message,$token);

        //Log::info($orderDetails);
        return $orderDetails;

    }


    private function sendMessageToBuyer($orderId,$sellerId,$buyerId,$message,$token){


        $curlOpt_URL = 'https://api.mercadolibre.com/messages/packs/'.$orderId.'/sellers/'.$sellerId.'?tag=post_sale';



        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $curlOpt_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
            "from":{"user_id":"'.$sellerId.'"},
            "to":{"user_id":"'.$buyerId.'"},
            "text":'.json_encode($message).'
        }',
        CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token,
                'Content-Type: application/json'
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
