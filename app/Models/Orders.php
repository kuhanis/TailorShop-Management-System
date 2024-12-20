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
        'status', 'link_activated_at',
        'link_status',
        'is_ready_to_collect',
        'paid_at',
        'image_path'
    ];
 
     public function customer(){
         return $this->belongsTo(Customer::class);
     }

    public function getOrderLinkAttribute()
    {
        return $this->access_token ? route('orders.view', $this->access_token) : null;
    }

    public function isLinkExpired(): bool
    {
        if (!$this->link_activated_at) return false;
        
        $expiryDate = Carbon::parse($this->link_activated_at)
            ->addDays(config('app.link_retention_days'));
        return $expiryDate->isPast();
    }

    public function getDaysUntilExpiry(): int
    {
        if (!$this->link_activated_at) return config('app.link_retention_days');
        
        $expiryDate = Carbon::parse($this->link_activated_at)
            ->addDays(config('app.link_retention_days'));
        return max(0, now()->diffInDays($expiryDate, false));
    }
}