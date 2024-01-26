<?php
namespace App\Services\MercadoLibre;

//use Illuminate\Support\Facades\Log;

class MLSendMessages {

   
    //Funcion para enviar un mensaje a un usuario en la api de Mercado Libre Mexico
    public function sendMessageToBuyer($id_mercadolibre,$orderId,$sellerId,$buyerId,$message) {
        
        $orderService = new MLApiServices($id_mercadolibre);
        return $orderService->sendMessage($orderId,$sellerId,$buyerId,$message);
        
    }

}