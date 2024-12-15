<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Orders extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =[
        'customer_id','description',
        'received_on','received_by','amount_charged',
        'amount_paid','collecting_on','access_token',
        'status'
    ];
 
     public function customer(){
         return $this->belongsTo(Customer::class);
     }

    public function getOrderLinkAttribute()
    {
        return $this->access_token ? route('orders.view', $this->access_token) : null;
    }

    public function scopeExpired($query)
    {
        return $query->where('received_on', '<=', Carbon::now()->subDays(30))
                    ->whereNotNull('access_token');
    }

    public function retention()
    {
        return $this->hasOne(Retention::class, 'order_id');
    }
}