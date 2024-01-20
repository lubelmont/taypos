<?php

namespace App\Http\Controllers\MercadoLibre;

use App\Helpers\MercadoLibre\CallTokenSesion;
use App\Models\MercadoLibreTokenSesion;
use App\Models\MercadoLibreUsuario;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VerificationMercadoLibreController extends Controller
{
    public function getValue(Request $request)
    {
        Log::info('--- VerificationMercadoLibreController--->'.$request);

        if (!$request->has('code')) {
            return response()->json(['error' => 'Parámetro "code" no proporcionado.'], 401);
        }

        $code = $request->query('code');
        // $code = $request->query('code');
        
        Log::info('--- VerificationMercadoLibreController.$code--->'.$code);

        if(!strpos($code, 'TG') === 0){
            return response()->json(['error' => 'Error de validación'], 403);
        }
        
        $MLTokenSesion = new CallTokenSesion();
       

        $tokenObj = $MLTokenSesion->getTokenFromMercadoLibre($code);
        //dd($tokenObjStr);
        
        
        if ($tokenObj === null) {
            return response()->json(['error' => '"Error al decodificar el JSON: " . json_last_error_msg()'], 403);
        } 
        //dd($tokenObj,$tokenObj["user_id"]);
        Log::info('--- VerificationMercadoLibreController.$tokenObjStr--->'.json_encode($tokenObj));

        MercadoLibreTokenSesion::updateOrcreate(
                ["id_mercadolibre"=>$tokenObj["user_id"]],$tokenObj);

                // "user_id" => ""
                // "token_type" => ""
                // "expires_in" => 21600
                // "scope" => "offline_access read write"
                // "user_id" => 
                // "refresh_token" => "TG-656ca360f046ac0001597861-212466595"

        
        //TODO: check if the user already exist, then don't create or update, if not exist, create
       
        $userDetails = $MLTokenSesion->getUserDetails($tokenObj["user_id"],$tokenObj["access_token"]);

        $idUsuario = Auth::id();

        MercadoLibreUsuario::updateOrcreate(["id_mercadolibre"=>$tokenObj["user_id"]],
                ["user_id"=>$idUsuario,
                "nickname"=>$userDetails["nickname"],
                "registration_date"=>$userDetails["registration_date"],
                "first_name"=>$userDetails["first_name"],
                "last_name"=>$userDetails["last_name"],
                "gender"=>$userDetails["gender"],
                "country_id"=>$userDetails["country_id"],
                "email"=>$userDetails["email"]]);

        //return response($userDetails, 200);
        return redirect('mercadolibre/config');

        //return response()->json(['hub.challenge' => $value]);
    }



}
