<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyMeasurement extends Model
{
    protected $fillable = [
        'customer_id',
        'body_name',
        'shoulder',
        'chest',
        'waist',
        'hips',
        'dress_length',
        'wrist',
        'skirt_length',
        'armpit'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
