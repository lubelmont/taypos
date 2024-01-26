<?php

namespace App\Services\SimCo;

use App\Models\MercadoLibreOrderItem;
use App\Models\ESimGoOrder;
use App\Models\ESimGoOrderAssignments;
use App\Services\ESimGo\ESimGoApiService;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\FuncCall;

class ProcessOrders {

    public function processMercadoLibreOrder($orderId) {

        Log::debug("processMercadoLibreOrder = $orderId");

        $items = MercadoLibreOrderItem::where('order_id', $orderId)->get();
        
        foreach ($items as $key => $item) {
            $orderFrom = "ML-".$orderId."-".$item['item_id'];
            $this->getSimFromESim($orderFrom,$item['seller_sku'],$item['quantity']);
        }

        return true;

    }

    public function processSimCoPortalOrder($orderId,$orderKey,$itemSku,$quantity) {

        Log::debug("processSimCoPortalOrder = $orderId");
        Log::debug("processSimCoPortalOrder itemSku = $itemSku");
        Log::debug("processSimCoPortalOrder quantity = $quantity");


        $orderFrom = "SIMCO-".$orderId."-".$orderKey;
        try {
        
            $this->getSimFromESim($orderFrom,$itemSku,$quantity);
        
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        return true;

    }

    public function updateSimCoPortalOrder($orderId,$data){
        $simCoPortalApi = new SimCoPortalApiService();
        return $simCoPortalApi->updateOrderStatus($orderId,$data);
    }


    private function getSimFromESim($orderFrom, $itemSku, $quantity) {

        //Change "type" from "validate" = test  to "transaction" = production
        $type = env('APP_ENV') === 'prod' ?  'transaction': 'transaction';

        $orderData = [
            "type" => $type,
            "assign" => true,
            "Order" => [
                [
                    "type" => "bundle",
                    "quantity" => intval($quantity),
                    "item" => $itemSku,
                ]
            ]
        ];
        
        $esimService = new ESimGoApiService('SIMCO');
        $response = $esimService->createOrderService($orderData);

        
        if($type == "validate"){
            if($response['valid'] == false){
                Log::info("Error al validar la orden de esim");
                return false;
            }
            $response['status']= "completed";
            $response['statusMessage']= "TEST Order completed: 1 eSIMs assigned";
            
            if($itemSku == "esims_1GB_7D_REUP_U"){
                $response['orderReference']= "1dac1233-0f35-4f4b-9142-ebb632e0b5a1";
            
            }else if($itemSku == "esims_1GB_7D_RNA_U"){
                $response['orderReference']= "44a61f8f-0083-45b2-898d-72fc2e0811fc";
            
            }else{
                $response['orderReference']= "44a61f8f-0083-45b2-898d-72fc2e0811fc";
            }

            //44a61f8f-0083-45b2-898d-72fc2e0811fc
            //1dac1233-0f35-4f4b-9142-ebb632e0b5a1
            
        }

        Log::debug(class_basename(__CLASS__) . " response ->esimService->createOrderService(orderData) ");
        Log::debug('type='.$type);
        Log::debug($response);
        Log::debug("--response---");

        $newOrder = $this->saveOrder($orderFrom,$itemSku,$quantity,$response);


        //TODO: Put this method in ESimGoApiService
        if ($response['status']!== 'completed') {
            $message = "Invalid status message !completed: ". $response['status'];
            $errorObj = [
                'orderData' => $orderData,
                'response' => $response,
                'statusMessage' => $response['statusMessage'],
                'message' => $message,
            ];
            Log::error($message);
            throw new \Exception(json_encode($errorObj));
           
        }

        
        
        #LONG APROACH----------------------
        #The bundle is now in your inventory and needs to be applied to a new eSIM using the following
        #API - https://docs.esim-go.com/api/#post-/esims/apply
        # https://help.esim-go.com/hc/en-gb/articles/11192513354257
        #----------------------------------
        
        #Go for quick aproach
        #item = sku
        
        $assingQR = $esimService->assignmentsQR($newOrder['orderReference']);
        
        
        Log::debug(class_basename(__CLASS__). " assingQR -> esimService->assignmentsQR(newOrder[orderReference])" );
        Log::debug($assingQR);
        Log::debug("--assingQR---");
        
        
        foreach ($assingQR as $item) {

            $item['orderReference'] = $newOrder['orderReference'];
            $assignmented = ESimGoOrderAssignments::create($item);

            if($assignmented){
                $esimService->updateSimDetails($item['iccid'],$orderFrom);
            }
        
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
    private function saveOrder($orderFrom,$itemSku,$quantity,$response){
       

        $orderFromResponse = $response["order"][0];

        $order = new ESimGoOrder();
        $order->type = $orderFromResponse['type'];
        $order->item = $itemSku;
        $order->quantity = $quantity;
        $order->subTotal = $orderFromResponse['subTotal'];
        $order->pricePerUnit = $orderFromResponse['pricePerUnit'];
        $order->total = $response['total'];
        $order->valid = $response['valid'] ?? 1;
        $order->currency = $response['currency'];
        $order->createdDate = $response['createdDate'];
        $order->assigned = $response['assigned'];
        $order->status = $response['status'];
        $order->statusMessage = $response['statusMessage'];
        $order->orderReference = $response['orderReference'];
        $order->order_from = $orderFrom;

        // check if exist order in database
        $exists = ESimGoOrder::where('order_from', $orderFrom)
                     ->where('item', $itemSku)
                     ->exists();

        if($exists){
            return $order;
        }
        
        $order->save();
        return $order;

    

    }
}