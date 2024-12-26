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

    public function isLinkExpired()
    {
        // Only check expiry for paid orders
        if (!$this->paid_at) {
            return false;  // Unpaid orders are never expired
        }

        if (!$this->access_token) {
            return true;  // No access token means expired/revoked
        }

        $period = config('retention.period', 400);
        $unit = config('retention.unit', 'days');
        
        $expiryDate = Carbon::parse($this->paid_at);
        
        switch($unit) {
            case 'minutes':
                $expiryDate->addMinutes($period);
                break;
            case 'hours':
                $expiryDate->addHours($period);
                break;
            case 'days':
                $expiryDate->addDays($period);
                break;
        }
        
        return Carbon::now()->gt($expiryDate);
    }

    public function getDaysUntilExpiry()
    {
        // Check if link is already revoked
        if ($this->link_status === 'revoked' || !$this->access_token) {
            return '<span class="badge badge-danger">Inactive</span>';
        }
        
        // If not paid yet
        if (!$this->paid_at) {
            return '<span class="badge badge-warning">Pending Payment</span>';
        }

        $period = config('retention.period', 400);
        $unit = config('retention.unit', 'days');
        
        $expiryDate = Carbon::parse($this->paid_at);
        
        switch($unit) {
            case 'minutes':
                $expiryDate->addMinutes($period);
                $diff = Carbon::now()->diffInMinutes($expiryDate, false);
                break;
            case 'hours':
                $expiryDate->addHours($period);
                $diff = Carbon::now()->diffInHours($expiryDate, false);
                break;
            case 'days':
                $expiryDate->addDays($period);
                $diff = Carbon::now()->diffInDays($expiryDate, false);
                break;
            default:
                return '<span class="badge badge-danger">Invalid Unit</span>';
        }

        if ($diff <= 0) {
            return '<span class="badge badge-danger">Expired</span>';
        }

        return '<span class="badge badge-success">Active (' . $diff . ' ' . $unit . ' left)</span>';
    }
}