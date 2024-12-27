<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateCustomerNames extends Command
{
    protected $signature = 'orders:populate-customer-names';
    protected $description = 'Populate customer names in order histories table';

    public function handle()
    {
        $this->info('Starting to populate customer names...');

        DB::table('order_histories as oh')
            ->join('customers as c', 'oh.customer_id', '=', 'c.id')
            ->whereNull('oh.customer_name')
            ->update(['oh.customer_name' => DB::raw('c.fullname')]);

        $this->info('Customer names populated successfully!');
    }
} 