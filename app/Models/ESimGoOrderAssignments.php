<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ESimGoOrderAssignments extends Model
{
    use HasFactory;

    protected $table = 'esimgo_orders_assignments';

    protected $fillable = [
        'iccid', 
        'matchingId', 
        'rspUrl', 
        'bundle', 
        'orderReference',
        'qr_svg'
    ];

    public function orderReferencer()
    {
        return $this->belongsTo(ESimGoOrder::class, 'orderReference', 'orderReference');
    }

    // public function setReferenceAttribute($value)
    // {
    //     $this->attributes['orderReference'] = $value;
    // }


}
