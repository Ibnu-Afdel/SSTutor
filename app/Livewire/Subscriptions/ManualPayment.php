<?php

namespace App\Livewire\Subscriptions;

use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManualPayment extends Component
{

    use WithFileUploads;

    public $plan = '';
    public $screenshot;
    public $notes;

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

    public function submit()
    {
        $this->validate([
            'plan' => 'required|in:monthly,yearly',
            'screenshot' => 'required|image|max:2048',
        ]);

        $selected = $this->plans[$this->plan];

        $path = $this->screenshot->store('screenshots', 'public');

        Subscription::create([
            'user_id' => Auth::id(),
            'payment_method' => 'manual',
            'status' => 'pending',
            'amount' => $selected['amount'],
            'duration_in_days' => $selected['duration'],
            'screenshot_path' => $path,
            'notes' => $this->notes,
        ]);

        session()->flash('success', 'Your payment request has been submitted and is pending admin review.');
        $this->reset(['plan', 'screenshot', 'notes']);
    }

    public function render()
    {
        return view('livewire.subscriptions.manual-payment');
    }
}
