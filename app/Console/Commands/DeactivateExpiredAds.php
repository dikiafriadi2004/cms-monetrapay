<?php

namespace App\Console\Commands;

use App\Models\Ad;
use Illuminate\Console\Command;

class DeactivateExpiredAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate ad slots that have passed their end date';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = Ad::where('is_active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', today())
            ->update(['is_active' => false]);

        $this->info("Deactivated {$count} expired ad(s).");

        return Command::SUCCESS;
    }
}
