<div>
    <div class="max-w-md p-6 mx-auto space-y-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800">
            @if ($isProUser)
                @if ($showExtendOption)
                    Extend Your Pro Subscription
                @else
                    Your Pro Subscription
                @endif
            @else
                Subscribe to Pro
            @endif
        </h2>

        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session()->has('info'))
            <div class="p-4 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg" role="alert">
                {{ session('info') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($isProUser)
            <div class="p-4 mb-4 border border-green-300 rounded-lg bg-green-50">
                <div class="flex items-center">
                    <svg class="w-8 h-8 mr-3 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-green-700">Your account is already Pro!</p>
                        <p class="text-xs text-green-600">
                            Subscription expires on: <span class="font-semibold">{{ $formattedExpiryDate }}</span>
                            ({{ $daysRemaining }} days remaining)
                        </p>
                    </div>
                </div>

                @if ($showExtendOption)
                    <div class="mt-3 text-sm">
                        <p class="text-green-700">You can extend your subscription below.</p>
                    </div>
                @endif
            </div>
        @endif

        @if ($hasPendingSubscription)
            <div class="p-4 text-sm border border-yellow-300 rounded-lg bg-yellow-50">
                <p class="font-semibold text-yellow-700">
                    You have {{ $pendingSubscriptionCount }} pending
                    payment{{ $pendingSubscriptionCount > 1 ? 's' : '' }}.
                </p>

                @if ($isProUser)
                    <p class="mt-1 text-yellow-600">
                        These are likely from previous payment attempts. Since you're already a Pro user, you can clean
                        them up.
                    </p>
                    <button wire:click="cleanupPendingSubscriptions" wire:loading.attr="disabled"
                        class="px-3 py-1 mt-2 text-xs font-medium text-yellow-800 transition bg-yellow-100 border border-yellow-300 rounded hover:bg-yellow-200">
                        Clean up pending payments
                    </button>
                @else
                    <p class="mt-1 text-yellow-600">
                        If you continue, your previous pending payment{{ $pendingSubscriptionCount > 1 ? 's' : '' }}
                        will be cancelled and replaced with this new payment.
                    </p>
                @endif
            </div>
        @endif

        @if (!$isProUser || $showExtendOption)
            <div class="space-y-4">
                <div>
                    <label for="duration" class="block mb-1 text-sm font-medium text-gray-700">Choose Your Plan:</label>
                    <select wire:model.live="duration" id="duration"
                        class="w-full p-2 border rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="30">Monthly - 199 ETB</option>
                        <option value="365">Yearly - 1999 ETB (Save over 16%)</option>
                    </select>
                </div>

                <div class="p-4 rounded-lg bg-gray-50">
                    <h3 class="font-medium text-gray-800">Plan Benefits:</h3>
                    <ul class="mt-2 space-y-1 text-sm text-gray-600 list-disc list-inside">
                        <li>Access to all premium courses</li>
                        <li>Downloadable resources</li>
                        <li>Priority support</li>
                        <li>No ads</li>
                    </ul>
                </div>

                <div class="p-4 border border-blue-100 rounded-lg bg-blue-50">
                    <p class="text-sm text-gray-700">Total Payment:
                        <span class="text-lg font-bold text-blue-700">{{ $amount }} ETB</span>
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        {{ $duration == 30 ? 'Monthly subscription - renew every month' : 'Yearly subscription - best value' }}
                    </p>
                </div>

                <button wire:click="pay" wire:loading.attr="disabled" wire:loading.class="opacity-75 cursor-not-allowed"
                    class="w-full px-4 py-3 text-white transition-colors bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <span wire:loading.remove>
                        @if ($isProUser && $showExtendOption)
                            Extend Subscription with Chapa
                        @else
                            Pay with Chapa
                        @endif
                    </span>
                    <span wire:loading>
                        <svg class="inline w-4 h-4 mr-2 -ml-1 text-white animate-spin"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Processing...
                    </span>
                </button>

                <p class="mt-2 text-xs text-center text-gray-500">
                    Secure payment powered by Chapa. Your payment information is processed securely.
                </p>
            </div>
        @else
            <div class="p-5 text-center rounded-lg bg-blue-50">
                <svg class="w-12 h-12 mx-auto mb-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h3 class="mb-2 text-lg font-medium text-blue-800">Your subscription is active!</h3>
                <p class="mb-3 text-sm text-blue-600">
                    You have {{ $daysRemaining }} days remaining on your current subscription.
                </p>
                <p class="text-xs text-blue-500">
                    You'll be able to extend your subscription when it gets closer to the expiry date.
                </p>
            </div>
        @endif

        @if (env('APP_DEBUG', false))
            <div class="pt-4 mt-8 text-xs text-gray-500 border-t">
                <p class="font-medium">Developer Information:</p>
                <p>If payment appears to succeed but you're redirected to a 404, you can manually verify by visiting:
                </p>
                <p class="p-2 mt-1 overflow-x-auto font-mono bg-gray-100 rounded">
                    {{ route('chapa.callback') }}?tx_ref=[transaction_reference]
                </p>
            </div>
        @endif
    </div>
</div>
