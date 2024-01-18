<?php
namespace App\Helpers\SimCo;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class WebhookProfileImpl implements WebhookProfile
{
    public function shouldProcess(Request $request): bool
    {
        
        Log::info($request->input());
        Log::info(json_encode($request->input()));
        $data = json_decode(json_encode($request->input()), true);
        
      

		$statusOrder = $data['status'];
        Log::info($statusOrder );
        
        if ($statusOrder == 'processing'){
            return true;
        }
        //else if ($statusOrder == 'pending_payment'){
          //  Log::info($data);
        //}
      
        return false;
    }
}
