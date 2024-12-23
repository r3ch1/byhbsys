<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'created_by',
        'type',
        'classification',
        'description',
        'payment_code',
        'payed_at',
        'expires_at'
    ];
}
