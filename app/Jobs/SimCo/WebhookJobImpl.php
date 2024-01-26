<?php
namespace App\Jobs\SimCo;


use \Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use Illuminate\Support\Facades\Log;
use App\Services\SimCo\ProcessOrders;
use App\Helpers\SimCo\SimCoApiToImpl;
use Carbon\Carbon;


class WebhookJobImpl extends ProcessWebhookJob
{
    public function handle()
    {
        $data = json_decode(json_encode($this->webhookCall->payload), true);
        //$headers = json_decode(json_encode($this->webhookCall->headers), true);
        //$headersSin = $this->webhookCall->headers;
        $date = Carbon::now();

         Log::debug("<----------------DataSimCo---------------->");
         Log::debug(json_encode($data, JSON_PRETTY_PRINT));
         Log::debug("<----------------Data---------------->");
         Log::debug("<----------------line_items---------------->");
         Log::debug($data['line_items']);
         Log::debug("<----------------line_items---------------->");

        $orderKey = $data['order_key'];
        $orderId = $data['id'];
        $lineItems = $data['line_items'];

        $processOrder = new ProcessOrders();
        $finishOrderArr =[] ;
        foreach($lineItems as $key => $item){

            Log::debug("<----------------line_items----------------START: >".$key);
            
            $itemSku = $item['sku'];
            $quantity = $item['quantity'];
            
            $finishOrderArr[]=$processOrder->processSimCoPortalOrder($orderId,$orderKey,$itemSku,$quantity);
            
            Log::debug("<----------------line_items----------------END: >".$key);
                    
        }

        //verifica si todos los items fueron procesados correctamente
        $finishOrder = true;
        foreach($finishOrderArr as $key => $item){
            Log::debug("<----------------finishOrderArr---------------->");
            Log::debug($key.' - '.$item);
            Log::debug("<----------------finishOrderArr---------------->");
            if($item == false){
                $finishOrder = false;
            }
        }

        if(!$finishOrder){

            Log::error("Error al procesar la orden");
            $error = [
                'payload' => $data,
                'date' => $date,
            ];

            throw new \Exception(json_encode($error));

        }


        $response = $processOrder->updateSimCoPortalOrder($orderId, 'completed');

        Log::info("WebhookJobImpl = response");
        Log::info($response);

        $responseData = json_decode($response, true);

        if (isset($responseData['data']['status'])) {
            // $responseData contains ['data']['status']
            $status = $responseData['data']['status'];
        
            // Validate the content of $status
            if ($status != 200) {
                // Handle the case where $status is not 200
                Log::error('Error: Status is not 200');
            }
        }


        
  
        // foreach ($headersSin as $key => $value) {
        //     //echo "$key: $value\n";
        //     Log::info($key."--------->".$value);
        // }
        // Log::info("<-------------------------------->");
        // Log::info("<---------------headers----------------->");
        // foreach ($headers as $key => $value) {
        //     //echo "$key: $value\n";
        //     Log::info($key."--------->".$value);
        // }
        // Log::info("<-------------------------------->");
        
        
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

        $regex = '/<at>(.*?)<\/at>/s';

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