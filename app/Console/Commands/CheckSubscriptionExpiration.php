<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class CheckSubscriptionExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate expired subscriptions and remove pro access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $expiredSubscriptions = Subscription::where('status', 'active')
            ->where('expires_at', '<', $now)
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            $subscription->update(['status' => 'expired']);

            $subscription->user->update([
                'is_pro' => false,
                'pro_expires_at' => null,
                'subscription_status' => 'expired',
            ]);
        }

        $this->info("Checked " . $expiredSubscriptions->count() . " subscriptions.");

        return self::SUCCESS;
    }

    public function schedule(Schedule $schedule): void
    {
        $schedule->daily();
    }
}
