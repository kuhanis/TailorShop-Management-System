<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Log;

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

        // Adjust the return statement to show the correct time left
        if ($unit === 'minutes') {
            return '<span class="badge badge-success">Active (' . $diff . ' minutes left)</span>';
        } else {
            return '<span class="badge badge-success">Active (' . $diff . ' ' . $unit . ' left)</span>';
        }
    }

    public function getSecondsUntilExpiry()
    {
        // Check if the order is paid
        if (!$this->paid_at) {
            return 0; // If not paid, return 0 seconds
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

        // Calculate the difference in seconds
        $secondsLeft = Carbon::now()->diffInSeconds($expiryDate);

        return max(0, $secondsLeft); // Ensure it doesn't return negative values
    }

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($order) {
            // Only create OrderHistory when paid_at changes from null to a value
            if ($order->isDirty('paid_at') && 
                $order->paid_at !== null && 
                $order->getOriginal('paid_at') === null) {  // Check if it was previously unpaid
                
                Log::info('Creating OrderHistory for paid Order', [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'paid_at' => $order->paid_at,
                    'original_paid_at' => $order->getOriginal('paid_at')
                ]);

                // Check if OrderHistory already exists for this order
                $existingHistory = OrderHistory::where([
                    'customer_id' => $order->customer_id,
                    'description' => $order->description,
                    'received_on' => $order->created_at->toDateString(),
                    'amount_charged' => $order->amount_charged,
                ])->exists();  // Using exists() instead of first() for efficiency

                if (!$existingHistory) {
                    // Get customer name before creating history
                    $customer = Customer::find($order->customer_id);
                    
                    OrderHistory::create([
                        'customer_id' => $order->customer_id,
                        'customer_name' => $customer ? $customer->fullname : null,
                        'description' => $order->description,
                        'received_on' => $order->created_at->toDateString(),
                        'amount_charged' => $order->amount_charged,
                        'processed_by' => auth()->user()->name ?? 'System',
                        'staff_id' => auth()->id()
                    ]);
                }
            }
        });
    }
}