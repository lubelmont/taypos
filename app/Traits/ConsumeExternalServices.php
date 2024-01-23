<?php

namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;



trait ConsumeExternalServices
{

    /**
     * Send a request to any service
     * @return array|string
     */
    public function makeRequest($method, $requestUrl, $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {


        $client = new Client([
            'base_uri' => $this->baseUri
        ]);

        if (method_exists($this, 'resolveAuthorization')) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        try{

            $response = $client->request($method, $requestUrl, [
                $isJsonRequest ? 'json' : 'form_params' => $formParams,
                'headers' => $headers,
                'query' => $queryParams,
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {

            if ($e->hasResponse()) {
                $errorMessage = $e->getResponse()->getBody();
                Log::error("message: " . $errorMessage);
            } else {
                Log::error("message: The esim-go API is not available");
            }
        }

        $response = $response->getBody();

        if (method_exists($this, 'decodeResponse')) {
            $response = $this->decodeResponse($response);
        }

        if (method_exists($this, 'checkIfErrorResponse')) {
            $this->checkIfErrorResponse($response);
        }

        return $response;
    }
}   