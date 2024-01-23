<?php
namespace App\Services\MercadoLibre;

use App\Helpers\MercadoLibre\CallOrders;
use App\Models\MercadoLibreNotification;
use App\Models\MercadoLibreOrder;
use App\Models\MercadoLibreOrderItem;
use Illuminate\Support\Facades\Log;

class SendMessages {

   
    //Funcion para enviar un mensaje a un usuario en la api de Mercado Libre Mexico
    public function sendMessageToBuyer($id_mercadolibre,$orderId,$sellerId,$buyerId,$message) {
        
        $orderService = new MercadoLibreApiServices($id_mercadolibre);
        return $orderService->sendMessage($orderId,$sellerId,$buyerId,$message);
        
    }

}