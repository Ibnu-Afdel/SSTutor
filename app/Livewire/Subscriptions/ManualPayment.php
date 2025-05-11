<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use App\Traits\CloudinaryUpload;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class ManualPayment extends Component
{
    use WithFileUploads, CloudinaryUpload;

    public $plan = '';
    public $screenshot;
    public $notes;

    public $isProUser = false;
    public $proExpiresAt = null;
    public $showExtendOption = false;
    public $cleanedPendingSubscriptions = false;

    public array $plans = [
        'monthly' => [
            'label' => 'Monthly - 200 ETB',
            'amount' => 200,
            'duration' => 30
        ],
        'yearly' => [
            'label' => 'Yearly - 2000 ETB',
            'amount' => 2000,
            'duration' => 365
        ],
    ];


    public function mount()
    {
        $user = Auth::user();

        if ($user) {
            $this->isProUser = $user->is_pro;
            $this->proExpiresAt = $user->pro_expires_at;

            if ($this->isProUser && $this->proExpiresAt) {

                $expiryDate = new \DateTime($this->proExpiresAt);
                $now = new \DateTime();

                if ($expiryDate > $now) {
                    $interval = $now->diff($expiryDate);

                    $daysRemaining = ($interval->days !== false) ? $interval->days : 0;

                    if ($daysRemaining <= 3) {
                        $this->showExtendOption = true;
                    }
                } else {
                    $this->isProUser = false;
                }
            }
        }
    }


    public function submit()
    {
        $this->validate([
            'plan' => 'required|in:' . implode(',', array_keys($this->plans)), // Dynamic validation based on keys
            'screenshot' => 'nullable|image|max:2048',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        if (!$user) {
            session()->flash('error', 'You must be logged in to submit a request.');
            return;
        }

        $selectedPlanData = $this->plans[$this->plan];
        $screenshotData = null;

        try {
            // Upload to Cloudinary if screenshot exists
            if ($this->screenshot) {
                $screenshotData = $this->uploadToCloudinary($this->screenshot, 'subscription_screenshots');
                
                if (!$screenshotData) {
                    throw new \Exception("Failed to upload screenshot.");
                }
            }

            Subscription::create([
                'user_id' => $user->id,
                'payment_method' => 'manual',
                'status' => 'pending',
                'amount' => $selectedPlanData['amount'],
                'duration_in_days' => $selectedPlanData['duration'],
                'screenshot_path' => $screenshotData,
                'notes' => $this->notes,
                'transaction_reference' => null,
            ]);

            session()->flash('success', 'Your payment request has been submitted successfully and is pending admin review.');
            $this->reset(['plan', 'screenshot', 'notes']);
        } catch (\Exception $e) {
            // If there's an error and we have a Cloudinary upload, try to delete it
            if ($screenshotData && isset($screenshotData['public_id'])) {
                $this->deleteFromCloudinary($screenshotData['public_id']);
            }
            
            session()->flash('error', 'An error occurred while submitting your request: ' . $e->getMessage() . ' Please try again.');
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
            $cancelledCount = 0;
            $errorCount = 0;
            foreach ($pendingSubscriptions as $pendingSub) {
                try {

                    if ($pendingSub->status === 'pending') {
                        $updated = $pendingSub->update([
                            'status' => 'cancelled',
                            'notes' => 'Manually cancelled by user via cleanup button'
                        ]);
                        if ($updated) {
                            $cancelledCount++;
                        } else {
                            $errorCount++;
                        }
                    }
                } catch (QueryException $e) {

                    if (str_contains($e->getMessage(), 'CHECK constraint failed')) {
                        session()->flash('error', "Could not cancel one or more subscriptions due to a database rule (possibly already processed or in an unexpected state). Please contact support if issues persist.");
                    } else {
                        session()->flash('error', "A database error occurred while cancelling subscriptions.");
                    }
                } catch (\Exception $e) {
                    session()->flash('error', "An unexpected error occurred while cancelling subscriptions.");
                }
            }

            if ($cancelledCount > 0) {
                session()->flash('success', "Successfully cancelled {$cancelledCount} pending subscription(s).");
                $this->cleanedPendingSubscriptions = true;
                $this->dispatch('$refresh');
            } elseif ($errorCount == 0) {
                session()->flash('info', 'No pending subscriptions needed cancellation or were found in a cancellable state.');
            }
        } else {
            session()->flash('info', 'No pending subscriptions found to cancel.');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $hasPendingSubscription = false;
        $pendingSubscriptionCount = 0;
        $formattedExpiryDate = null;
        $daysRemaining = null;

        if ($user) {

            $user->refresh();
            $this->isProUser = $user->is_pro;
            $this->proExpiresAt = $user->pro_expires_at;

            if (!$this->cleanedPendingSubscriptions) {
                $pendingSubscriptionCount = Subscription::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count();
                $hasPendingSubscription = $pendingSubscriptionCount > 0;
            } else {

                $hasPendingSubscription = false;
                $pendingSubscriptionCount = 0;
            }


            if ($this->isProUser && $this->proExpiresAt) {
                try {
                    $expiryDate = new \DateTime($this->proExpiresAt);
                    $formattedExpiryDate = $expiryDate->format('F j, Y');

                    $now = new \DateTime();

                    if ($expiryDate > $now) {
                        $interval = $now->diff($expiryDate);

                        $daysRemaining = ($interval->days !== false) ? $interval->days : 0;


                        $this->showExtendOption = ($daysRemaining <= 3);
                    } else {

                        $daysRemaining = 0;
                        $this->showExtendOption = false;
                    }
                } catch (\Exception $e) {
                    $formattedExpiryDate = 'Invalid Date';
                    $daysRemaining = null;
                    $this->showExtendOption = false;
                }
            } else {

                $formattedExpiryDate = null;
                $daysRemaining = null;
                $this->showExtendOption = false;
            }
        } else {
            $this->isProUser = false;
            $this->showExtendOption = false;
            $hasPendingSubscription = false;
            $pendingSubscriptionCount = 0;
            $formattedExpiryDate = null;
            $daysRemaining = null;
        }


        return view('livewire.subscriptions.manual-payment', [
            'hasPendingSubscription' => $hasPendingSubscription,
            'pendingSubscriptionCount' => $pendingSubscriptionCount,
            'isProUser' => $this->isProUser,
            'formattedExpiryDate' => $formattedExpiryDate,
            'daysRemaining' => $daysRemaining,
            'showExtendOption' => $this->showExtendOption,
        ]);
    }
}
