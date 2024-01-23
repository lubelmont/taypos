<?php
namespace App\Jobs\MercadoLibre;

use App\Helpers\MercadoLibre\CallMessages;
use \Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Illuminate\Support\Facades\Log;
use App\Services\MercadoLibre\ProcessOrders as ProcessOrdersML;
use App\Services\SimCo\ProcessOrders as ProcessOrdersSimCo;
use App\Models\MercadoLibreOrder;


class WebhookJobImpl extends ProcessWebhookJob
{
    public function handle()
    {
        $payload = json_decode(json_encode($this->webhookCall->payload), true);
        //$headers = json_decode(json_encode($this->webhookCall->headers));
        


        $resource = $payload['resource'];
        
         Log::debug("<----------------data---------------->");
         Log::debug($payload);
         $resource = $payload['resource'];
         Log::debug($resource);
         Log::debug("<----------------data---------------->");




        $textToContinue = "orders";
        if (stripos($resource,$textToContinue ) !== false) {
            
            $processOrdersML = new ProcessOrdersML();
            $order = $processOrdersML->processOrder($payload);

            Log::debug("message orderId=" . $order->id);
            Log::debug("message payload= ");
            Log::debug($payload);
            Log::debug("--------------------");
            Log::debug($order);
            Log::debug("--------------------");
           

            if(!$order){
                return false;
            }
            
            $processOrdersSimCo = new ProcessOrdersSimCo();
            $isProcess = $processOrdersSimCo->processMercadoLibreOrder($order->id);
            
            
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