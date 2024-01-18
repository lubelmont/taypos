<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLibrePayment extends Model
{
    use HasFactory;
    protected $table = 'ml_payments'; 
    protected $fillable = [
        'order_id',
        'payer_id',
        'collector_id',
        'card_id',
        'reason',
        'site_id',
        'payment_method_id',
        'currency_id',
        'installments',
        'issuer_id',
        'coupon_id',
        'operation_type',
        'payment_type',
        'status',
        'status_code',
        'status_detail',
        'transaction_amount',
        'transaction_amount_refunded',
        'taxes_amount',
        'shipping_cost',
        'coupon_amount',
        'overpaid_amount',
        'total_paid_amount',
        'installment_amount',
        'deferred_period',
        'date_approved',
        'transaction_order_id',
        'date_created',
        'date_last_modified',
        'marketplace_fee',
        'reference_id',
        'authorization_code',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
