<?php 

namespace App\Services\MercadoLibre;

use App\Models\MercadoLibreNotification;

class MLNotifications {
 

    public function updateOrCreate($notification){

        $resourceParts = explode('/', $notification['resource']);
        $orderId = end($resourceParts);

        $notification['id']= $notification['_id'];
        $notification['order_id']= $orderId;

        return MercadoLibreNotification::updateOrCreate(["id"=>$notification["id"]],$notification);
    }



}