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
            'mesage' => 'OK'
        ];

       $headers= ['Content-Type'=> 'application/json'];
        return response()->json($data,200, $headers);
    }
}
