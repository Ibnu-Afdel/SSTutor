<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use Livewire\Component;
use Illuminate\Support\Carbon;

class ManageSubscriptions extends Component
{

    public function approve($subscriptionId)
    {
        $subscription = Subscription::findOrFail($subscriptionId);

        // Calculate dates
        $startDate = now();
        $expiresAt = now()->addDays($subscription->duration_in_days);

        // Update subscription
        $subscription->update([
            'status' => 'active',
            'starts_at' => $startDate,
            'expires_at' => $expiresAt,
            'paid_at' => now(),
        ]);

        // Update user
        $user = $subscription->user;
        $user->update([
            'is_pro' => true,
            'pro_expires_at' => $expiresAt,
            'subscription_type' => $subscription->payment_method,
            'subscription_status' => 'active',
        ]);
    }

    public function reject($subscriptionId, $note = 'Payment not accepted')
    {
        $subscription = Subscription::findOrFail($subscriptionId);

        $subscription->update([
            'status' => 'rejected',
            'notes' => $note,
        ]);

        $subscription->user->update([
            'is_pro' => false,
            'subscription_status' => 'rejected',
        ]);
    }
    public function render()
    {
        $subscriptions = Subscription::where('status', 'pending')->latest()->get();
        return view('livewire.admin.manage-subscriptions', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
