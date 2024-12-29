<?php
namespace App\Console\Commands;

use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RevokeExpiredLinks extends Command
{
    protected $signature = 'links:revoke-expired';
    
    public function handle()
    {
        $period = config('retention.period', 400);
        $unit = config('retention.unit', 'days');
        
        $expiryDate = Carbon::now();
        switch($unit) {
            case 'minutes': $expiryDate = $expiryDate->subMinutes($period); break;
            case 'hours': $expiryDate = $expiryDate->subHours($period); break;
            case 'days': $expiryDate = $expiryDate->subDays($period); break;
        }

        Orders::where('link_activated_at', '<=', $expiryDate)
              ->where('link_status', 'active')
              ->update([
                  'link_status' => 'revoked',
                  'access_token' => null
              ]);
    }
}
