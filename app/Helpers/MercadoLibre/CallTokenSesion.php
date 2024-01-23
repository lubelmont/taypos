<?php

namespace App\Helpers\MercadoLibre;

use App\Models\MercadoLibreTokenSesion;
use Carbon\Carbon;

class CallTokenSesion
{

    public function getToken($id_mercadolibre)
    {
            $tokenData = MercadoLibreTokenSesion::where("id_mercadolibre",$id_mercadolibre)->first();
            // $tokenIsValid = $this->isTokenValid($tokenData->updated_at , $tokenData->expires_in);

            if($this->isTokenValid($tokenData["updated_at"] , $tokenData["expires_in"]))
            {
                return $tokenData["access_token"];
            }

            return $this->refreshTokenFromMercadoLibre($tokenData["refresh_token"]);



    }

    public function getTokenFromMercadoLibre($codeTG)
    {
        
        $redirect_uri= env('APP_URL').'/mercadolibre/auth';

        $curl = curl_init();
        
        /*
        grant_type=authorization_code
        client_id=6722168577139405
        client_secret=QnX5pqJjdgiwAVCKuN6vTqyNpcHyaP90
        code=TG-6562b80c8f6e660001591aed-1566150988
        redirect_uri=https%3A%2F%2Flubelsoft.com
        code_verifier=07j0odAODqYzaM-VLyX8GtbmS8xpvJOCduPI9oKSpxk'
        */

        $client_id = env('MERCADO_LIBRE_CLIENT_ID');
        $client_secret = env('MERCADO_LIBRE_CLIENT_SECRET');
        
        $params = [
            'grant_type' => 'authorization_code',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'code' => $codeTG,
            'redirect_uri' => $redirect_uri,
            'code_verifier' => '07j0odAODqYzaM-VLyX8GtbmS8xpvJOCduPI9oKSpxk'
        ];
        
        $curOpT_Postfield = http_build_query($params);



        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.mercadolibre.com/oauth/token',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $curOpT_Postfield,
          CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'content-type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);


        $tokenObj = json_decode($response , true);

        if ($tokenObj === null && json_last_error() !== JSON_ERROR_NONE) {
            return null;
        } 

        return $tokenObj;
        
    }

    public function refreshTokenFromMercadoLibre($codeTG){
        $curl = curl_init();
        
        $client_id = env('MERCADO_LIBRE_CLIENT_ID');
        $client_secret = env('MERCADO_LIBRE_CLIENT_SECRET');


        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $codeTG,
        ];
        
        $curOpT_Postfield = http_build_query($params);

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadolibre.com/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $curOpT_Postfield,
        CURLOPT_HTTPHEADER => array(
            'accept: application/json',
            'content-type: application/x-www-form-urlencoded'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $tokenObj = json_decode($response , true);

        if ($tokenObj === null && json_last_error() !== JSON_ERROR_NONE) {
            return null;
        } 


        MercadoLibreTokenSesion::updateOrcreate(
            ["id_mercadolibre"=>$tokenObj["user_id"]],$tokenObj);


        return $tokenObj["access_token"];
    }



    public function getUserDetails($user_id, $access_token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadolibre.com/users/'.$user_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$access_token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $userObj = json_decode($response , true);

        if ($userObj === null && json_last_error() !== JSON_ERROR_NONE) {
            return null;
        } 

        return $userObj;

    }

    private static function isTokenValid($createdAt, $expiresInSeconds)
    {

        $createdAtDate = Carbon::parse($createdAt)->setTimezone('UTC');
        $expiresIn = $createdAtDate->addSeconds($expiresInSeconds);
        $now = Carbon::now('UTC');

        // true si aún está vigente, false si ha expirado
        return $now->lt($expiresIn); 

    }
}
