<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoLibreNotification extends Model
{
    use HasFactory;
    protected $table = 'ml_notifications'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'topic',
        'resource',
        'order_id', 
        'user_id',
        'application_id',
        'sent',
        'attempts',
        'received',
    ];

    protected $casts = [
        'sent' => 'datetime',
        'received' => 'datetime',
    ];



}
