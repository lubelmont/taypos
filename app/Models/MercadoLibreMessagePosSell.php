<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLibreMessagePosSell extends Model
{
    use HasFactory;
    protected $table = 'ml_message_pos_sells'; 
    protected $fillable = ['user_id', 'position', 'days_alive', 'message_to_bill','methods_payments','payment_type' ]; 

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
