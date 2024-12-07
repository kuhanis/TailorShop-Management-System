<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodyMeasurement extends Model
{
    protected $fillable = [
        'customer_id',
        'body_name',
        'neck',
        'shoulder_width',
        'chest',
        'waist',
        'hip',
        'arm_length',
        'sleeve',
        'torso_length'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
