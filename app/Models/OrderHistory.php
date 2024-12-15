<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $fillable = [
        'customer_id',
        'description',
        'received_on',
        'amount_charged',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
} 