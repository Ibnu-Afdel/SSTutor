<?php

namespace App\Livewire;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SubscribeWithChapa extends Component
{
    public $duration = 30;
    public $amount = 199;
    public $isProUser = false;
    public $proExpiresAt = null;
    public $showExtendOption = false;

    public function mount()
    {
        $this->updateAmount();
        $user = Auth::user();

        if ($user) {
            $this->isProUser = $user->is_pro;
            $this->proExpiresAt = $user->pro_expires_at;
            $this->checkExtendOption();
            $this->cleanupPendingSubscriptions();
        }
    }

    public function updatedDuration($value)
    {
        $this->updateAmount();
    }

    public function pay()
    {
        return redirect()->route('chapa.pay', ['duration' => $this->duration]);
    }

    public function cleanupPendingSubscriptions()
    {
        $user = Auth::user();
        if (!$user) return;

        $updated = Subscription::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'cancelled',
                'notes' => 'Manually cancelled by user'
            ]);

        if ($updated > 0) {
            session()->flash('success', 'Successfully cancelled all pending subscriptions.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $pendingSubscriptionCount = 0;
        $formattedExpiryDate = null;
        $daysRemaining = null;

        if ($user) {
            $pendingSubscriptionCount = Subscription::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();

            if ($this->proExpiresAt) {
                $expiryDate = new \DateTime($this->proExpiresAt);
                $formattedExpiryDate = $expiryDate->format('F j, Y');
                $daysRemaining = (new \DateTime())->diff($expiryDate)->days;
            }
        }

        return view('livewire.subscribe-with-chapa', [
            'hasPendingSubscription' => $pendingSubscriptionCount > 0,
            'pendingSubscriptionCount' => $pendingSubscriptionCount,
            'formattedExpiryDate' => $formattedExpiryDate,
            'daysRemaining' => $daysRemaining,
        ]);
    }

    private function updateAmount()
    {
        $this->amount = $this->duration == 30 ? 199 : 1999;
    }

    private function checkExtendOption()
    {
        if ($this->proExpiresAt) {
            $expiryDate = new \DateTime($this->proExpiresAt);
            $daysDiff = (new \DateTime())->diff($expiryDate)->days;
            $this->showExtendOption = $daysDiff <= 3;
        }
    }
}
