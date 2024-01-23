<?php
namespace App\Services\MercadoLibre;

use App\Helpers\MercadoLibre\CallOrders;
use App\Models\MercadoLibreNotification;
use App\Models\MercadoLibreOrder;
use App\Models\MercadoLibreOrderItem;
use Illuminate\Support\Facades\Log;

class ProcessOrders {

    public function processOrder($notification) {

        Log::debug("<----------------notification---------------->");
        Log::debug(json_encode($notification, JSON_PRETTY_PRINT));
        Log::debug("<----------------notification---------------->");
        
        MercadoLibreNotification::updateOrCreate(["_id"=>$notification["_id"]],$notification);
        
        // ejecutar el servicio de orders para obtener los detalles de la orden

        $id_mercadolibre = $notification["user_id"];
        $resource = $notification["resource"];
        
        $orderService = new MercadoLibreApiServices($id_mercadolibre);
        
        $order = $orderService->getOrderDetailsCall($resource);

                
         Log::debug("<----------------order---------------->");
         Log::debug(json_encode($order, JSON_PRETTY_PRINT));
         Log::debug("<----------------order[status]---------------->");
         Log::debug($order["status"]);
         Log::debug("<----------------order---------------->");

        if($order["status"] !== "paid"){
            return false;
        }
        
        $exists = MercadoLibreOrder::where('id', $order["id"])->first();

        

        $newOrder = $exists;

        if (!$exists){

            $order = array_filter($order, function ($value) {
                return !is_null($value);
            });
    
            $order["seller_id"] = $notification["user_id"];
            $order["fulfilled"] = $order["fulfilled"] ?? 0;
            
            //Fill the buyer data
            $order["buyer_id"] = $order["buyer"]["id"];
            $order["buyer_nickname"] = $order["buyer"]["nickname"];
            $order["buyer_first_name"] = $order["buyer"]["first_name"];
            $order["buyer_last_name"] = $order["buyer"]["last_name"];
    
            
            $newOrder = MercadoLibreOrder::create($order);
        } 

        Log::debug("<----------------exists[fulfilled]---------------->");
        Log::debug($newOrder["fulfilled"]);
        Log::debug("<----------------exists[fulfilled]---------------->");
        
        if($newOrder["fulfilled"]!==0){
            Log::debug("<----------------CHALE---------------->");
            return $newOrder;
        }
      
        

        $orderItems=$order["order_items"];
        
        foreach ($orderItems as $key => $value) {
        
            $itemToSave["order_id"] = $newOrder->id;
            $itemToSave["item_id"] = $value["item"]["id"];
            $itemToSave["title"] = $value["item"]["title"];
            $itemToSave["category_id"] = $value["item"]["category_id"];
            $itemToSave["seller_sku"] = $value["item"]["seller_sku"];
            $itemToSave["quantity"] = $value["quantity"];
            $itemToSave["unit_price"] = $value["unit_price"];
            $itemToSave["full_unit_price"] = $value["full_unit_price"];
            $itemToSave["sale_fee"] = $value["sale_fee"] ?? 0;
            $itemToSave["currency_id"] = $value["currency_id"];

            $itemToSave = array_filter($itemToSave, function ($value) {
                return !is_null($value);
            });
            
            $exists = MercadoLibreOrderItem::where('item_id', $itemToSave["item_id"])->exists();
            
            if($exists){
                MercadoLibreOrderItem::where('item_id', $itemToSave["item_id"])->update($itemToSave);
            }else{

                MercadoLibreOrderItem::create($itemToSave);
            }
        }
        
       return $newOrder;

    }

}

?>