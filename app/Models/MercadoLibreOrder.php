<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLibreOrder extends Model
{
    use HasFactory;
    protected $table = 'ml_orders'; 


    protected $fillable = [
        'order_id',
        'date_created',
        'last_updated',
        'expiration_date',
        'date_closed',
        'comment',
        'pack_id',
        'pickup_id',
        'fulfilled',
        'hidden_for_seller',
        'buying_mode',
        'shipping_cost',
        'application_id',
        'total_amount',
        'paid_amount',
        'currency_id',
        'status',
        'status_detail',
        'seller_id',
        'buyer_id',
        'buyer_nickname',
        'buyer_first_name',
        'buyer_last_name'
    ];


    protected $casts = [
        'date_created' => 'datetime',
        'last_updated' => 'datetime',
        'expiration_date' => 'datetime',
        'date_closed' => 'datetime',
    ];

    public function orderItems()
    {
        return $this->hasMany(MercadoLibreOrderItem::class);
    }

    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }

    public function seller()
    {
        return $this->belongsTo(MercadoLibreUsuario::class, 'id_mercadolibre');
    }
}
