<?php

namespace App\Services\SimCo;

use App\Models\MercadoLibreOrderItem;
use App\Models\ESimGoOrder;
use App\Models\ESimGoOrderAssignments;
use App\Services\SimCo\ESIMService;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;

class ProcessOrders {

    public function processMercadoLibreOrder($orderId) {

        Log::info("processMercadoLibreOrder = $orderId");

        $items = MercadoLibreOrderItem::where('order_id', $orderId)->get();
        
        foreach ($items as $key => $item) {
            $orderFrom = "ML-".$orderId."-".$item['item_id'];
            $this->getSimFromESim($orderFrom,$orderId,$item['seller_sku'],$item['quantity']);
        }

        return true;

    }

    public function processSimCoPortalOrder($orderId,$orderKey,$itemSku,$quantity) {

        Log::info("processSimCoPortalOrder = $orderId");
        
        try {
        
        $this->getSimFromESim($orderKey,$orderId,$itemSku,$quantity);
        
        } catch (\Exception $e) {
            return false;
            error_log($e->getMessage());
        }

        return true;

    }

    public function updateSimCoPortalOrder($orderId,$data){

    }


    private function getSimFromESim($orderFrom,$orderId, $itemSku, $quantity) {

        //Change "type" from "validate" = test  to "transaction" = production
        $type = env('APP_ENV') === 'prod' ?  'transaction': 'validate';

        $orderData = [
            "type" => $type,
            "assign" => true,
            "Order" => [
                [
                    "type" => "bundle",
                    "quantity" => $quantity,
                    "item" => $itemSku,
                ]
            ]
        ];
        
        $esimService = new ESIMService();
        $response = $esimService->createOrder($orderData);

        Log::info("response");
        Log::info($response);
        Log::info("--response---");

        
        if($type == "validate"){
            if($response['valid'] == false){
                Log::info("Error al validar la orden de esim");
                return false;
            }
            $response['status']= "completed";
            $response['statusMessage']= "Order completed: 2 eSIMs assigned";
            $response['orderReference']= "00de4daf-168e-40d2-9c59-bef33b788db1";
            
        }

        Log::info("response 2");
        Log::info($response);
        Log::info("--response 2---");
        
        $newOrder = $this->saveOrder($orderFrom,$orderId,$itemSku,$quantity,$response);





        if (strpos($response['statusMessage'], 'Order completed') === false) {
            //throw new Exception('Invalid status message: Order already completed');
            return false;
        }



        #LONG APROACH----------------------
        #The bundle is now in your inventory and needs to be applied to a new eSIM using the following
        #API - https://docs.esim-go.com/api/#post-/esims/apply
        # https://help.esim-go.com/hc/en-gb/articles/11192513354257
        #----------------------------------

        #Go for quick aproach
        #item = sku
        
        $assingQR = $esimService->assignmentsQR($newOrder['orderReference']);


        Log::info("assingQR");
        Log::info($assingQR);
        Log::info("--assingQR---");


        foreach ($assingQR as $item) {
            $item['orderReference'] = $newOrder['orderReference'];
            ESimGoOrderAssignments::create($item);
        }
        

        


    }

    /**
     * Save order in database
     * 
     *  Respuesta de api de esim:  
     *   {
     *       "order": [
     *           {
     *               "type": "bundle",
     *               "item": "esim",
     *               "quantity": 1,
     *               "subTotal": 6,
     *               "pricePerUnit": 6
     *           }
     *       ],
     *       "total": 6,
     *       "valid": true,
     *       "currency": "USD",
     *       "createdDate": "2023-12-11T17:26:03.471467363Z",
     *       "assigned": true,
     *       "status": "valid",
     *       "orderReference": "5f9c3b6b-5b7b-4b7e-8b0a-5b9e1b2b4b4b"
     *   }
     */
    private function saveOrder($orderFrom,$orderId,$itemSku,$quantity,$response){
       

        $orderFromResponse = $response["order"][0];

        $order = new ESimGoOrder();
        $order->type = $orderFromResponse['type'];
        $order->item = $itemSku;
        $order->quantity = $quantity;
        $order->subTotal = $orderFromResponse['subTotal'];
        $order->pricePerUnit = $orderFromResponse['pricePerUnit'];
        $order->total = $response['total'];
        $order->valid = $response['valid'];
        $order->currency = $response['currency'];
        $order->createdDate = $response['createdDate'];
        $order->assigned = $response['assigned'];
        $order->status = $response['status'];
        $order->statusMessage = $response['statusMessage'];
        $order->orderReference = $response['orderReference'];
        $order->order_from = $orderFrom."-".$orderId;


    


        // check if exist order in database
        $exists = ESimGoOrder::where('order_from', $orderFrom."-".$orderId)->exists();

        if($exists){
            return $order;
        }
        
        $order->save();
        return $order;

    

    }
}