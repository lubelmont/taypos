<?php
namespace App\Services\MercadoLibre;

use App\Helpers\MercadoLibre\CallOrders;
use App\Models\MercadoLibreNotification;
use App\Models\MercadoLibreOrder;
use App\Models\MercadoLibreOrderItem;
use Illuminate\Support\Facades\Log;

class ProcessOrders {

    public function processOrder($notification) {

        // Log::info("<----------------notification---------------->");
        // Log::debug($notification);
        // Log::info("<----------------notification---------------->");
        
        
        MercadoLibreNotification::updateOrCreate(["_id"=>$notification["_id"]],$notification);
        
        $orderService = new CallOrders();
        $order = $orderService->getOrderDetails($notification);

                
        // Log::info("<----------------order---------------->");
        // Log::debug($order);
        // Log::info("<----------------order---------------->");

        if($order["status"] !== "paid"){
            return false;
        }
        
        $exists = MercadoLibreOrder::where('id', $order["id"])->first();
        
        if($exists){
            //TODO: if order exists, check if all ready processed
            return $exists;
        }
        
        $order = array_filter($order, function ($value) {
            return !is_null($value);
        });

        $order["seller_id"] = $notification["user_id"];
        $order["fulfilled"] = $order["fulfilled"] ?? false;
        
        //Fill the buyer data
        $order["buyer_id"] = $order["buyer"]["id"];
        $order["buyer_nickname"] = $order["buyer"]["nickname"];
        $order["buyer_first_name"] = $order["buyer"]["first_name"];
        $order["buyer_last_name"] = $order["buyer"]["last_name"];

        
        $newOrder = MercadoLibreOrder::create($order);

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
            $itemToSave["sale_fee"] = $value["sale_fee"];
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

        // Log::info("<----------------newOrder---------------->");
        // Log::debug($newOrder);
        // Log::info("<----------------newOrder---------------->");

       return $newOrder;

    }

    private function saveOrder() {



    }
}

?>