<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLibreUsuario extends Model
{
    use HasFactory;
    protected $table = 'ml_usuarios';

    protected $fillable = [
        'user_id',
        'id_mercadolibre',
        'nickname',
        'registration_date',
        'first_name',
        'last_name',
        'gender',
        'country_id',
        'email',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];

     public function user()
     {
         return $this->belongsTo(User::class);
     }

}
