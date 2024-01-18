<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ESimGoOrderApply extends Model
{
    use HasFactory;
    protected $table = 'esimgo_orders_apply'; 

    protected $fillable = [
        'iccid', 
        'matchingId', 
        'rspUrl', 
        'bundle', 
        'orderReference'
    ];

    public function order()
    {
        return $this->belongsTo(ESimGoOrder::class, 'orderReference', 'orderReference');
    }

    
}
