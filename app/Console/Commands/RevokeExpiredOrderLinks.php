<?php

namespace App\Console\Commands;

use App\Models\Orders;
use Illuminate\Console\Command;

class RevokeExpiredOrderLinks extends Command
{
    protected $signature = 'orders:revoke-expired-links';
    protected $description = 'Revoke access tokens for orders older than 30 days';

    public function handle()
    {
        $expiredOrders = Orders::expired()->get();
        
        foreach ($expiredOrders as $order) {
            $order->update(['access_token' => null]);
        }

        $this->info(count($expiredOrders) . ' order links have been revoked.');
    }
} 