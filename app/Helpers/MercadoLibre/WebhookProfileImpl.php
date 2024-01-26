<?php
namespace App\Helpers\MercadoLibre;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;
use App\Models\MercadoLibreNotification;

class WebhookProfileImpl implements WebhookProfile
{
    public function shouldProcess(Request $request): bool
    {

        $data = json_decode(json_encode($request->input()), true);

        
        $resource = isset($data['resource']) ? $data['resource'] : 'default_value';
        

        $textToContinue = "orders";
        if (stripos($resource,$textToContinue ) !== false) {

            $notification = MercadoLibreNotification::where('resource', $resource)->first();
            Log::debug("<----------------notification already exist---------------->.$resource");

            $envi = env('APP_ENV');
            if( $notification){
              return false;
            }

            //if($exist){
              //  return false;
            //}


            
            return true;
        }

        return false;
    }
}
