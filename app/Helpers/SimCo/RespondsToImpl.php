<?php
namespace App\Helpers\SimCo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookConfig;
use Symfony\Component\HttpFoundation\Response;
use Spatie\WebhookClient\WebhookResponse\RespondsToWebhook;

class RespondsToImpl implements RespondsToWebhook
{
    public function respondToValidWebhook(Request $request, WebhookConfig $config): Response
    {


        $data = [
            'type' => 'message',
            'text' => '**You typed**:  \n **Commands supported**: adaptive-card, hero-card, list-card, o365-card and thumbnail-card ',
            'email' => 'johndoe@example.com'
        ];

        
        
        $json = json_encode($data);
        
       // header('Content-Type: application/json');
       $headers= ['Content-Type'=> 'application/json'];

        $responseMsg = '{ "type": "message", "text": "**You typed**:  \n **Commands supported**: adaptive-card, hero-card, list-card, o365-card and thumbnail-card " }';
        
        Log::info("<----------------respondToValidWebhook---------------->".$json );
        return response()->json($data,200, $headers);
    }
}
