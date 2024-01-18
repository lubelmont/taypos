<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLibreTokenSesion extends Model
{
    use HasFactory;

    protected $table = 'ml_token_sesions'; 

    protected $fillable = [
        'id_mercadolibre',
        'access_token',
        'token_type',
        'expires_in',
        'scope',
        'refresh_token',
    ];
}
