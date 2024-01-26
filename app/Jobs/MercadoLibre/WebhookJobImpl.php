<?php
namespace App\Jobs\MercadoLibre;

use App\Helpers\MercadoLibre\CallMessages;
use \Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Illuminate\Support\Facades\Log;
use App\Services\MercadoLibre\MLProcessOrders;
use App\Services\SimCo\ProcessOrders as ProcessOrdersSimCo;
use App\Models\MercadoLibreOrder;


class WebhookJobImpl extends ProcessWebhookJob
{
    public function handle()
    {
        $payload = json_decode(json_encode($this->webhookCall->payload), true);
        
        $resource = $payload['resource'];
        
         Log::debug("<----------------data---------------->");
         Log::debug($payload);
         $resource = $payload['resource'];
         Log::debug($resource);
         Log::debug("<----------------data---------------->");




        $textToContinue = "orders";
        if (stripos($resource,$textToContinue ) !== false) {
            
            $processOrdersML = new MLProcessOrders();
            $order = $processOrdersML->processOrder($payload);

            Log::debug("message orderId=" . $order->order_id);
            Log::debug("--------------------");
            Log::debug($order);
            Log::debug("--------------------");
           

            if(!$order){
                return false;
            }
            
            $processOrdersSimCo = new ProcessOrdersSimCo();
            $isProcess = $processOrdersSimCo->processMercadoLibreOrder($order->order_id);
            
            
            if(!$isProcess){
                return false;
            }

            $sentMessage = new CallMessages();
            $sentMessage->sendMensajeCompleOrder($order);
            

            MercadoLibreOrder::where('id', $order["id"])->update(['fulfilled' => 1]);


        }
        
       
        http_response_code(200); //Acknowledge you received the response
    }
}