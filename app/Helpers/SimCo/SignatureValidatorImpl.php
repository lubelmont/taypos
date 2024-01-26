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
            $body = $request->getContent();
            $secret = env('SIMCO_WEBHOOK_SECRET');
            $expected_signature = base64_encode(hash_hmac('sha256', $body, $secret, true));
            $signature = $request->header('X-WC-Webhook-Signature');

            Log::debug('--- SignatureValidatorImpl::info--->');
            Log::debug('signature = '.$signature);
            Log::debug('ex_signature = '.$expected_signature);
            Log::debug('--- SignatureValidatorImpl::info--->');

            //dd('------>');
            return true;
        }
    }