<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Retention extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'link_expire'
    ];

    protected $dates = [
        'link_expire'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
} 