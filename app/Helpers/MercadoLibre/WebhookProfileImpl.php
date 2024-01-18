<?php
namespace App\Helpers\MercadoLibre;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class WebhookProfileImpl implements WebhookProfile
{
    public function shouldProcess(Request $request): bool
    {

        $data = json_decode(json_encode($request->input()), true);

        Log::info("<----------------data from ML---------------->");
        Log::info($data);
        Log::info("<----------------data from ML---------------->");
        
        $resource = isset($data['resource']) ? $data['resource'] : 'default_value';
        Log::info("<----------------resource---------------->");
        Log::info($resource);
        Log::info("<----------------resource---------------->");


        $textToContinue = "orders";
        if (stripos($resource,$textToContinue ) !== false) {
            Log::info("<----------------FALSO---------------->");
            return true;
        }

        return false;
    }
}
