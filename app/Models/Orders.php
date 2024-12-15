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
<<<<<<< HEAD
        'status'
=======
        'status', 'link_activated_at',
        'link_status'
>>>>>>> 4a5769f848a284b5056086ba49af2fdc66f8c887
    ];
 
     public function customer(){
         return $this->belongsTo(Customer::class);
     }

    public function getOrderLinkAttribute()
    {
        return $this->access_token ? route('orders.view', $this->access_token) : null;
    }

<<<<<<< HEAD
    public function scopeExpired($query)
    {
        return $query->where('received_on', '<=', Carbon::now()->subDays(30))
                    ->whereNotNull('access_token');
    }

    public function retention()
    {
        return $this->hasOne(Retention::class, 'order_id');
=======
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
>>>>>>> 4a5769f848a284b5056086ba49af2fdc66f8c887
    }
}