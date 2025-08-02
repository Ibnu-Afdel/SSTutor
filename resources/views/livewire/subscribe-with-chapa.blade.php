<div>
    <div class="max-w-2xl p-6 mx-auto space-y-6 bg-white border border-gray-200 shadow-lg sm:p-8 rounded-2xl">

        <div class="mb-4">
            <a href="{{ route('user.profile', ['username' => auth()->user()->username]) }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors duration-150 hover:text-indigo-600">
                <i class="mr-1 text-gray-400 fas fa-arrow-left fa-fw"></i>
                Back to Profile
            </a>
        </div>

        <h2 class="text-3xl font-bold text-gray-800">
            <i class="mr-2 text-indigo-500 fas fa-shopping-cart fa-fw"></i>
            @if ($isProUser)
                @if ($showExtendOption)
                    Extend Your Pro Subscription
                @else
                    Your Pro Subscription
                @endif
            @else
                Become a Pro Member
            @endif
        </h2>

        @if (session()->has('error'))
            <div class="flex items-start p-4 space-x-3 text-sm text-red-800 border border-red-200 rounded-lg bg-red-50"
                role="alert">
                <i class="mt-1 text-red-500 fas fa-exclamation-circle fa-fw"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if (session()->has('info'))
            <div class="flex items-start p-4 space-x-3 text-sm text-blue-800 border border-blue-200 rounded-lg bg-blue-50"
                role="alert">
                <i class="mt-1 text-blue-500 fas fa-info-circle fa-fw"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif
        @if (session()->has('success'))
            <div class="flex items-start p-4 space-x-3 text-sm text-green-800 border border-green-200 rounded-lg bg-green-50"
                role="alert">
                <i class="mt-1 text-green-500 fas fa-check-circle fa-fw"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if ($isProUser)
            <div class="p-5 border border-green-200 rounded-lg bg-gradient-to-br from-green-50 to-white">
                <div class="flex flex-col sm:flex-row sm:items-center">
                    <div
                        class="flex items-center justify-center w-12 h-12 mb-3 text-xl text-white rounded-full shadow shrink-0 bg-gradient-to-tr from-green-500 to-emerald-600 sm:mb-0 sm:mr-4">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="flex-grow text-center sm:text-left">
                        <p class="text-lg font-semibold text-green-800">You are a Pro Member!</p>
                        <p class="text-sm text-green-600">
                            <i class="mr-1 text-green-400 fas fa-calendar-check fa-fw"></i>
                            Expires on: <span class="font-medium">{{ $formattedExpiryDate }}</span>
                            (<span class="font-medium">{{ $daysRemaining }}</span> days remaining)
                        </p>
                    </div>
                </div>
                @if ($showExtendOption)
                    <p class="mt-3 text-sm text-center text-green-700 sm:text-left">You can extend your subscription
                        below.</p>
                @endif
            </div>
        @endif

        @if ($hasPendingSubscription)
            <div class="p-4 text-sm border rounded-lg border-amber-300 bg-amber-50/80">
                <div class="flex items-start space-x-3">
                    <i class="mt-1 text-amber-500 fas fa-exclamation-triangle fa-fw"></i>
                    <div>
                        <p class="font-semibold text-amber-800">
                            You have {{ $pendingSubscriptionCount }} pending
                            payment{{ $pendingSubscriptionCount > 1 ? 's' : '' }}.
                        </p>
                        @if ($isProUser)
                            <p class="mt-1 text-amber-700">
                                These might be from previous attempts. Since you're Pro, you can safely remove them.
                            </p>

                            <button wire:click="cleanupPendingSubscriptions" wire:loading.attr="disabled"
                                wire:target="cleanupPendingSubscriptions"
                                class="inline-flex items-center gap-1 px-3 py-1 mt-2 text-xs font-medium transition duration-150 ease-in-out border rounded-md text-amber-900 bg-amber-200 border-amber-300 hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="cleanupPendingSubscriptions"><i
                                        class="fas fa-broom fa-fw"></i> Clean up</span>
                                <span wire:loading wire:target="cleanupPendingSubscriptions"><i
                                        class="fas fa-spinner fa-spin"></i> Cleaning...</span>
                            </button>
                        @else
                            <p class="mt-1 text-amber-700">
                                Continuing will cancel the previous pending
                                payment{{ $pendingSubscriptionCount > 1 ? 's' : '' }} and create a new one.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if (!$isProUser || $showExtendOption)
            <div class="pt-6 space-y-6 border-t border-gray-200">

                <div>
                    <label for="duration" class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-gray-400 fas fa-calendar-alt fa-fw"></i>Choose Your Plan:
                    </label>
                    <div class="relative">

                        <select wire:model.live="duration" id="duration"
                            class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="30">Monthly - 199 ETB</option>
                            <option value="365">Yearly - 1999 ETB (Save over 16%)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-5 border border-gray-200 rounded-lg bg-gray-50/70">
                    <h3 class="mb-3 font-semibold text-gray-800">
                        <i class="mr-1 text-purple-500 fas fa-star fa-fw"></i> Plan Benefits:
                    </h3>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex items-center"><i
                                class="w-5 mr-2 text-center text-green-500 fas fa-check-circle fa-fw"></i>Access to all
                            premium courses</li>
                        <li class="flex items-center"><i
                                class="w-5 mr-2 text-center text-green-500 fas fa-check-circle fa-fw"></i>Downloadable
                            resources</li>
                        <li class="flex items-center"><i
                                class="w-5 mr-2 text-center text-green-500 fas fa-check-circle fa-fw"></i>Priority
                            support queue</li>
                        <li class="flex items-center"><i
                                class="w-5 mr-2 text-center text-green-500 fas fa-check-circle fa-fw"></i>Ad-free
                            experience</li>
                    </ul>
                </div>

                <div class="p-5 text-center border border-indigo-200 rounded-lg bg-indigo-50">
                    <p class="mb-1 text-sm font-medium text-indigo-700 uppercase">Total Amount</p>

                    <p class="text-3xl font-bold text-indigo-900">{{ $amount }} ETB</p>
                    <p class="mt-2 text-xs text-indigo-600">
                        <i class="mr-1 fas fa-info-circle fa-fw"></i>

                        {{ $duration == 30 ? 'Billed monthly' : 'Billed annually - Best Value!' }}
                    </p>
                </div>

                <div class="space-y-4 sm:space-y-0 sm:flex sm:gap-4">

                    <button wire:click="pay" wire:loading.attr="disabled" wire:target="pay"
                        wire:loading.class.remove="hover:from-purple-700 hover:to-indigo-800"
                        wire:loading.class="opacity-75 cursor-wait"
                        class="flex items-center justify-center w-full px-6 py-3 text-base font-bold text-white transition duration-150 ease-in-out border border-transparent rounded-lg shadow-md bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">

                        <span wire:loading.remove wire:target="pay">
                            <i
                                class="mr-2 fas {{ $isProUser && $showExtendOption ? 'fa-calendar-plus' : 'fa-credit-card' }} fa-fw"></i>
                            @if ($isProUser && $showExtendOption)
                                Extend with Chapa
                            @else
                                Pay with Chapa
                            @endif
                        </span>
                        <span wire:loading wire:target="pay">
                            <i class="mr-2 fas fa-spinner fa-spin"></i>
                            Processing...
                        </span>
                    </button>

                    <a href="{{ route('subscribe.manual') }}"
                        class="flex items-center justify-center w-full px-6 py-3 text-base font-bold text-indigo-700 transition duration-150 ease-in-out bg-white border border-indigo-300 rounded-lg shadow-sm hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="mr-2 fas fa-hand-holding-usd fa-fw"></i> Manual Payment
                    </a>
                </div>

                <p class="mt-3 text-xs text-center text-gray-500">
                    <i class="mr-1 fas fa-lock fa-fw"></i> Secure payment options available.
                </p>
            </div>
        @else
            <div class="p-6 text-center border border-blue-200 rounded-lg bg-blue-50">
                <i class="mb-4 text-5xl text-blue-500 fas fa-party-popper"></i>
                <h3 class="mb-2 text-lg font-semibold text-blue-800">Your Pro Subscription is Active!</h3>
                <p class="mb-3 text-sm text-blue-700">
                    Enjoy your premium access! You have <span class="font-medium">{{ $daysRemaining }}</span> days
                    remaining.
                </p>
                <p class="text-xs text-blue-600">
                    You'll be able to extend your plan closer to the expiry date.
                </p>
            </div>
        @endif

        {{-- @if (env('APP_DEBUG', false))
        <div class="pt-6 mt-6 text-xs text-gray-400 border-t border-gray-200">
            <p class="font-medium text-gray-500"><i class="mr-1 fas fa-bug"></i>Developer Information:</p>
            <p class="mt-1">Manual Chapa verification URL structure:</p>
            <p class="p-2 mt-1 overflow-x-auto font-mono text-gray-600 bg-gray-100 rounded">
                {{ route('chapa.callback') }}?tx_ref=[transaction_reference]
            </p>
        </div>
        @endif --}}

    </div>
</div>