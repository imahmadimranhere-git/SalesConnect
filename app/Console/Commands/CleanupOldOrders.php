<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class CleanupOldOrders extends Command
{
    protected $signature = 'orders:cleanup';

    protected $description = 'Soft-delete delivered or cancelled orders older than 1 month';

    public function handle(): void
    {
        $count = Order::whereIn('status', ['delivered', 'cancelled'])
            ->where('created_at', '<', now()->subMonth())
            ->delete();

        $this->info("{$count} old order(s) removed from active listing.");
    }
}