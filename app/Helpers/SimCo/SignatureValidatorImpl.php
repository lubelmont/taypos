<?php
namespace App\Helpers\SimCo;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Illuminate\Support\Facades\Log;
    
    class SignatureValidatorImpl implements SignatureValidator
    {
        public function isValid(Request $request, WebhookConfig $config): bool
        {
            
            Log::info('--- Log::info--->');
            //dd('------>');
            return true;
        }
    }