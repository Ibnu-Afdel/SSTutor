<div class="p-6 space-y-4">
    <h2 class="text-2xl font-bold">Pending Subscriptions</h2>

    @foreach ($subscriptions as $subscription)
        <div class="p-4 border rounded shadow-md">
            <p><strong>User:</strong> {{ $subscription->user->name }} ({{ $subscription->user->email }})</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($subscription->payment_method) }}</p>
            <p><strong>Amount:</strong> {{ $subscription->amount }}</p>
            <p><strong>Duration:</strong> {{ $subscription->duration_in_days }} days</p>

            @if ($subscription->screenshotUrl)
                <img src="{{ $subscription->screenshotUrl }}" alt="Screenshot"
                    class="w-40 h-auto mt-2">
            @endif

            <div class="mt-4 space-x-2">
                <button wire:click="approve({{ $subscription->id }})"
                    class="px-4 py-2 text-white bg-green-600 rounded">Approve</button>
                <button wire:click="reject({{ $subscription->id }})"
                    class="px-4 py-2 text-white bg-red-600 rounded">Reject</button>
            </div>
        </div>
    @endforeach
</div>
