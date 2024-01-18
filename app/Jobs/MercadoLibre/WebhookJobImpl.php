<?php
namespace App\Jobs\MercadoLibre;

use App\Helpers\MercadoLibre\CallMessages;
use \Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Illuminate\Support\Facades\Log;
use App\Helpers\MercadoLibre\CallOrders;
use App\Services\MercadoLibre\ProcessOrders as ProcessOrdersML;
use App\Services\SimCo\ProcessOrders as ProcessOrdersSimCo;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;


class WebhookJobImpl extends ProcessWebhookJob
{
    public function handle()
    {
        $payload = json_decode(json_encode($this->webhookCall->payload), true);
        $headers = json_decode(json_encode($this->webhookCall->headers));
        


        $resource = $payload['resource'];
        
        // Log::info("<----------------data---------------->");
        // Log::info($payload);
        // $resource = $payload['resource'];
        // Log::info($resource);
        // Log::info("<----------------data---------------->");

        // Log::info("<----------------headers---------------->");
        // Log::info($headers);
        // Log::info("<----------------headers---------------->");



        $textToContinue = "orders";
        if (stripos($resource,$textToContinue ) !== false) {
            
            $processOrdersML = new ProcessOrdersML();
            $order = $processOrdersML->processOrder($payload);
            Log::info("message orderId=" . $order->id);
            Log::info("message payload= ");
            Log::info($payload);
            Log::info("--------------------");
            

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
            




        }
        

        
        
        //$headersSin = $this->webhookCall->headers;
        //$date = Carbon::now();

        //Log::info("<----------------headersSin---------------->");
        //foreach ($headersSin as $key => $value) {
            //echo "$key: $value\n";
            //Log::info($key."--------->".$value);
        //}
        //Log::info("<-------------------------------->");
        //Log::info("<---------------headers----------------->");
        //foreach ($headers as $key => $value) {
            //echo "$key: $value\n";
            //Log::info($key."--------->".$value);
        //}
        //Log::info("<-------------------------------->");
        
        
        //Log::info("------------->".$data['text']);
       // Log::info("-------headers------>".$headers);
        //Log::info("--------headersSin----->".$headersSin);
       // Log::info($date);
        //$type_webhook =  $data['data']['object']['payment_status'];
        
        /*if ($type_webhook == 'paid'){
           
            (new ConektaController())->registrarPagos($data);
        }*/


// Convierte la respuesta en un objeto


// Accede a los valores del objeto
        // $object = $data->object;
        // $entry = $data->entry;
        // $id = $entry[0]->id;
        // $changes = $entry[0]->changes;
        // $field = $changes[0]->field;
        // $value = $changes[0]->value;

        //$message = $data['text'];

        //$regex = '/<at>(.*?)<\/at>/s';

        //$message = preg_replace($regex, "", $message );

       // if (preg_match($regex, $message, $matches)) {
        //    $message =  $matches[0];
        //} 
/*
        $client = new Client();
        $headers = [
          'Content-Type' => 'application/json',
          'Accept' => 'application/json',
          'Authorization' => 'Basic ODUyYmQ5NTE6V1d4QXA3VHlVcmxkSFpXRQ=='
        ];
        $body = '{
          "from": "14157386102",
          "to": "525532253784",
          "message_type": "text",
          "text": "'.$message.'",
          "channel": "whatsapp"
        }';

        $result = $client->post('https://messages-sandbox.nexmo.com/v1/messages', [
            'body' => $body,
            'headers' => $headers
        ]);

    */
        //dd($result);

        //$responseMsg = '{ "type": "message", "text": "**You typed**: '.$data['text'] . ' \n **Commands supported**: adaptive-card, hero-card, list-card, o365-card and thumbnail-card " }';

       
        http_response_code(200); //Acknowledge you received the response
    }
}