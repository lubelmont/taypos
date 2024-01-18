<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLibreOrderItem extends Model
{
    use HasFactory;
    protected $table = 'ml_order_items'; 

    protected $fillable = [
        'order_id',
        'item_id',
        'title',
        'category_id',
        'variation_id',
        'seller_custom_field',
        'warranty',
        'condition',
        'seller_sku',
        'global_price',
        'net_weight',
        'quantity',
        'requested_quantity',
        'picked_quantity',
        'unit_price',
        'full_unit_price',
        'currency_id',
        'manufacturing_days',
        'sale_fee',
        'listing_type_id',
        'base_exchange_rate',
        'base_currency_id',
        'element_id',
    ];

    public function order()
    {
        return $this->belongsTo(MercadoLibreOrder::class);
    }
}
