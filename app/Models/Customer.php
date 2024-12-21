<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'address',
        'phone',
        'email'
    ];

    public function bodyMeasurements()
    {
        return $this->hasMany(BodyMeasurement::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function orderHistories()
    {
        return $this->hasMany(OrderHistory::class, 'customer_id');
    }

}

