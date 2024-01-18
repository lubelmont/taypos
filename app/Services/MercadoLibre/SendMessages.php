<?php
namespace App\Services\MercadoLibre;

use App\Helpers\MercadoLibre\CallOrders;
use App\Models\MercadoLibreNotification;
use App\Models\MercadoLibreOrder;
use App\Models\MercadoLibreOrderItem;
use Illuminate\Support\Facades\Log;

class SendMessages {

    public function sendMessage($orderId, $message) {
        
        $orderService = new CallOrders();
        $order = $orderService->getOrderDetails($orderId);

        $order["seller_id"] = $order["seller"]["id"];
        $order["buyer_id"] = $order["buyer"]["id"];
        $order["order_id"] = $order["id"];
        $order["message"] = $message;
        
        $order = array_filter($order, function ($value) {
            return !is_null($value);
        });
        
        $order["message"] = $message;
        
        $orderService->sendMessage($order);
        
        return true;
    }

    //Funcion para enviar un mensaje a un usuario en la api de Mercado Libre Mexico
    public function sendMessageToUser($userId, $message) {
        
        $orderService = new CallOrders();
        $order = $orderService->sendMessageToUser($userId, $message);
        
        return true;
    }

}