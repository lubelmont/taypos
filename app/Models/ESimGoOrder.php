<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ESimGoOrder extends Model
{
    use HasFactory;

    protected $table = 'esimgo_orders'; 

    protected $fillable = [
        'type', 
        'item', 
        'quantity', 
        'subTotal', 
        'pricePerUnit', 
        'total', 
        'valid', 
        'currency', 
        'createdDate', 
        'assigned', 
        'status', 
        'orderReference', 
        'order_id',
        'order_from',
        'statusMessage'
    ];

    protected $casts = [
        'createdDate' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsToMany(ESimGoOrderApply::class, 'orderReference', 'orderReference');
    }

    public function assingnment()
    {
        return $this->belongsToMany(ESimGoOrderAssignments::class, 'orderReference', 'orderReference');
    }

}
