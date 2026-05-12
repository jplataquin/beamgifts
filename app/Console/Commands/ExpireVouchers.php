<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Voucher;

class ExpireVouchers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vouchers:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically transition active vouchers to expired status if their date has passed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Voucher::where('status', 'active')
            ->where('expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Successfully expired {$count} vouchers.");
    }
}
