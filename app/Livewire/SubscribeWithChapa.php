<?php

namespace App\Livewire;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SubscribeWithChapa extends Component
{
    public $duration = 30;
    public $amount = 199;
    public $processingPayment = false;
    public $isProUser = false;
    public $proExpiresAt = null;
    public $showExtendOption = false;
    public $cleanedPendingSubscriptions = false;

    public function mount()
    {
        $this->amount = $this->duration == 30 ? 199 : 1999;
        $user = Auth::user();

        if ($user) {
            $this->isProUser = $user->is_pro;
            $this->proExpiresAt = $user->pro_expires_at;

            if ($this->isProUser) {
                $pendingSubscriptions = Subscription::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->get();

                if ($pendingSubscriptions->count() > 0) {

                    foreach ($pendingSubscriptions as $pendingSub) {
                        $pendingSub->update([
                            'status' => 'cancelled',
                            'notes' => 'Auto-cancelled: User already has active pro status'
                        ]);
                    }

                    $this->cleanedPendingSubscriptions = true;
                }

                if ($this->proExpiresAt) {
                    $expiryDate = new \DateTime($this->proExpiresAt);
                    $now = new \DateTime();
                    $daysDiff = $now->diff($expiryDate)->days; // btw, $startDate->diff($endDate) => It calculates the difference from $startDate to $endDate.

                    if ($daysDiff <= 3) {
                        $this->showExtendOption = true;
                    }
                }
            }
        }
    }

    public function updatedDuration($value)
    {
        $this->amount = $value == 30 ? 199 : 1999;
    }

    public function pay()
    {
        if ($this->processingPayment) {
            session()->flash('info', 'Your payment is already being processed. Please wait.');
            return;
        }

        $this->processingPayment = true;

        $user = Auth::user();

        if (!($this->isProUser && !empty($this->proExpiresAt))) {
            $activeSubscription = Subscription::where('user_id', $user->id)
                ->where('status', 'active')
                ->first();

            if ($activeSubscription) {
                $expiresAt = $activeSubscription->expires_at ? new \DateTime($activeSubscription->expires_at) : null;

                if ($expiresAt && $expiresAt > now()) {
                    $this->processingPayment = false;
                    session()->flash('info', 'You already have an active subscription that expires on ' . $expiresAt->format('Y-m-d') . '.');
                    return;
                }
            }
        }

        $pendingSubscriptions = Subscription::where('user_id', $user->id)
            ->where('payment_method', 'chapa')
            ->where('status', 'pending')
            ->get();

        if ($pendingSubscriptions->count() > 0) {

            foreach ($pendingSubscriptions as $pendingSub) {
                $pendingSub->update([
                    'status' => 'rejected',
                    'notes' => 'Cancelled due to new subscription attempt'
                ]);
            }
            session()->flash('info', 'Your previous pending payment(s) have been cancelled. Proceeding with new payment.');
        }

        $tx_ref = 'TX-' . Str::uuid();

        try {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'payment_method' => 'chapa',
                'status' => 'pending',
                'amount' => $this->amount,
                'duration_in_days' => $this->duration,
                'transaction_reference' => $tx_ref,
            ]);

            $callback_url = route('chapa.callback');

            $data = [
                'amount' => $this->amount,
                'currency' => 'ETB',
                'email' => $user->email,
                'first_name' => $user->name,
                'tx_ref' => $tx_ref,
                'callback_url' => $callback_url,
                'return_url' => $callback_url,
                'customization' => ['title' => 'Pro Subscription', 'description' => 'Monthly or Yearly Pro Access'],
            ];

            $response = Http::withToken(env('CHAPA_SECRET_KEY'))
                ->post('https://api.chapa.co/v1/transaction/initialize', $data);


            if ($response->successful() && isset($response['data']['checkout_url'])) {
                return redirect()->to($response['data']['checkout_url']);
            }

            $errorMessage = 'Failed to initialize payment.';
            if ($response->json('message')) {
                $errorMessage .= ' Error: ' . $response->json('message');
            }

            $subscription->update([
                'status' => 'failed',
                'notes' => 'Failed to initialize: ' . ($response->json('message') ?? 'Unknown error')
            ]);

            $this->processingPayment = false;
            session()->flash('error', $errorMessage . ' Please try again later.');
        } catch (\Exception $e) {

            $this->processingPayment = false;
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function cleanupPendingSubscriptions()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $pendingSubscriptions = Subscription::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        if ($pendingSubscriptions->count() > 0) {

            foreach ($pendingSubscriptions as $pendingSub) {
                $pendingSub->update([
                    'status' => 'cancelled',
                    'notes' => 'Manually cancelled by user'
                ]);
            }

            session()->flash('success', 'Successfully cancelled all pending subscriptions.');
            $this->cleanedPendingSubscriptions = true;
        } else {
            session()->flash('info', 'No pending subscriptions found.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $hasPendingSubscription = false;
        $pendingSubscriptionCount = 0;
        $formattedExpiryDate = null;

        if ($user) {
            if (!$this->cleanedPendingSubscriptions) {
                $pendingSubscriptionCount = Subscription::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count();

                $hasPendingSubscription = $pendingSubscriptionCount > 0;
            }

            if ($this->proExpiresAt) {
                $expiryDate = new \DateTime($this->proExpiresAt);
                $formattedExpiryDate = $expiryDate->format('F j, Y');

                $now = new \DateTime();
                $daysRemaining = $now->diff($expiryDate)->days;
            }
        }

        return view('livewire.subscribe-with-chapa', [
            'hasPendingSubscription' => $hasPendingSubscription,
            'pendingSubscriptionCount' => $pendingSubscriptionCount,
            'isProUser' => $this->isProUser,
            'formattedExpiryDate' => $formattedExpiryDate,
            'daysRemaining' => $daysRemaining ?? null,
            'showExtendOption' => $this->showExtendOption
        ]);
    }
}
