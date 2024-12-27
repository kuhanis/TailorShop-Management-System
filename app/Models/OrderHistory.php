<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class OrderHistory extends Model
{
    protected $fillable = [
        'customer_id',
        'customer_name',
        'description',
        'received_on',
        'amount_charged',
        'processed_by',
        'staff_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderHistory) {
            Log::info('Creating OrderHistory record', [
                'customer_id' => $orderHistory->customer_id,
                'customer_name' => $orderHistory->customer_name
            ]);

            if ($orderHistory->customer_id) {
                $customer = Customer::find($orderHistory->customer_id);
                Log::info('Found customer', [
                    'customer' => $customer ? $customer->toArray() : null
                ]);

                if ($customer) {
                    $orderHistory->customer_name = $customer->fullname;
                    Log::info('Set customer_name', [
                        'customer_name' => $orderHistory->customer_name
                    ]);
                }
            }
        });

        static::created(function ($orderHistory) {
            Log::info('OrderHistory record created', [
                'id' => $orderHistory->id,
                'customer_id' => $orderHistory->customer_id,
                'customer_name' => $orderHistory->customer_name
            ]);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
} 