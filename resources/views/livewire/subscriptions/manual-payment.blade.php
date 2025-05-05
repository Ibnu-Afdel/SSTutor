<div>
    <div class="max-w-2xl p-6 mx-auto space-y-6 bg-white border border-gray-200 shadow-lg sm:p-8 rounded-2xl">

        <div class="mb-4">
            <a href="{{ route('subscribe.index') }}"
                class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors duration-150 hover:text-indigo-600">
                <i class="mr-1 text-gray-400 fas fa-arrow-left fa-fw"></i>
                Back to Subscription Options
            </a>
        </div>

        <h2 class="text-3xl font-bold text-gray-800">
            <i class="mr-2 text-indigo-500 fas fa-hand-holding-usd fa-fw"></i>
            @if ($isProUser)
                @if ($showExtendOption)
                    Extend Pro Manually
                @else
                    Your Pro Subscription
                @endif
            @else
                Manual Subscription Request
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
                        manually below.</p>
                @endif
            </div>
        @endif

        @if ($hasPendingSubscription && (!$isProUser || $showExtendOption))
            <div class="p-4 text-sm border rounded-lg border-amber-300 bg-amber-50/80">
                <div class="flex items-start space-x-3">
                    <i class="mt-1 text-amber-500 fas fa-exclamation-triangle fa-fw"></i>
                    <div>
                        <p class="font-semibold text-amber-800">
                            You have {{ $pendingSubscriptionCount }} pending manual
                            payment{{ $pendingSubscriptionCount > 1 ? 's' : '' }}.
                        </p>
                        @if ($isProUser)
                            <p class="mt-1 text-amber-700">
                                These might be from previous attempts. Since you're Pro, you can safely remove them or
                                proceed to submit a new extension request. Submitting a new request will <span
                                    class="font-semibold">not</span> automatically cancel these older pending manual
                                ones unless your admin cleans them up.
                            </p>

                            <button wire:click="cleanupPendingSubscriptions" wire:loading.attr="disabled"
                                wire:target="cleanupPendingSubscriptions"
                                class="inline-flex items-center gap-1 px-3 py-1 mt-2 text-xs font-medium transition duration-150 ease-in-out border rounded-md text-amber-900 bg-amber-200 border-amber-300 hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="cleanupPendingSubscriptions"><i
                                        class="fas fa-broom fa-fw"></i> Clean up Pending</span>
                                <span wire:loading wire:target="cleanupPendingSubscriptions"><i
                                        class="fas fa-spinner fa-spin"></i> Cleaning...</span>
                            </button>
                        @else
                            <p class="mt-1 text-amber-700">
                                Continuing will create a new manual submission request. Your previous pending manual
                                payment{{ $pendingSubscriptionCount > 1 ? 's' : '' }} will remain until reviewed or
                                cleaned up by an administrator.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if (!$isProUser || $showExtendOption)

            <div class="p-5 border border-blue-200 rounded-lg bg-blue-50/70">
                <h3 class="mb-3 font-semibold text-gray-800">
                    <i class="mr-1 text-blue-500 fas fa-info-circle fa-fw"></i> How to
                    @if ($isProUser && $showExtendOption)
                        Extend Manually:
                    @else
                        Subscribe Manually:
                    @endif
                </h3>
                <ol class="space-y-2 text-sm text-gray-700 list-decimal list-inside">
                    <li>Choose the plan you want below.</li>
                    <li>
                        Make a payment of the corresponding amount to:
                        <strong
                            class="block p-2 mt-1 font-medium text-blue-900 bg-blue-100 border border-blue-200 rounded">
                            Bank Name: []<br>
                            Account Name: []<br>
                            Account Number: []<br>
                            Telebirr: []
                        </strong>
                    </li>
                    <li>Take a clear screenshot of the payment confirmation.</li>
                    <li>Upload the screenshot using the form below.</li>
                    <li>Optionally add any notes (like the transaction ID).</li>
                    <li>Submit your request. We will verify the payment and activate/extend your subscription (usually
                        within 24 hours).</li>
                </ol>
            </div>

            <form wire:submit.prevent="submit" class="pt-6 space-y-6 border-t border-gray-200">

                <div>
                    <label for="plan" class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-gray-400 fas fa-list-alt fa-fw"></i>Choose Your Plan:
                    </label>
                    <div class="relative">
                        <select wire:model.defer="plan" id="plan" required
                            class="block w-full px-4 py-3 pr-8 leading-tight text-gray-700 bg-white border border-gray-300 rounded-lg appearance-none focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="" disabled>Select a plan</option>
                            @foreach ($plans as $key => $planOption)
                                <option value="{{ $key }}">{{ $planOption['label'] }} -
                                    {{ $planOption['price'] ?? 'N/A' }} ETB</option>
                            @endforeach
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                            </svg>
                        </div>
                    </div>
                    @error('plan')
                        <p class="flex items-center mt-1 text-xs text-red-600"><i
                                class="mr-1 fas fa-exclamation-circle fa-fw"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="screenshot" class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-gray-400 fas fa-camera fa-fw"></i>Upload Payment Screenshot:
                    </label>
                    <input id="screenshot" type="file" wire:model="screenshot" required
                        class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">

                    <div wire:loading wire:target="screenshot" class="mt-2 text-sm text-gray-500"><i
                            class="mr-1 fas fa-spinner fa-spin"></i> Uploading screenshot...</div>

                    @if ($screenshot && !$errors->has('screenshot'))
                        <div class="mt-2">
                            <p class="mb-1 text-xs text-gray-600">Screenshot preview:</p>
                            <img src="{{ $screenshot->temporaryUrl() }}" alt="Screenshot Preview"
                                class="object-contain h-32 border border-gray-200 rounded-lg shadow-sm">
                        </div>
                    @endif

                    @error('screenshot')
                        <p class="flex items-center mt-1 text-xs text-red-600"><i
                                class="mr-1 fas fa-exclamation-circle fa-fw"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block mb-2 text-sm font-medium text-gray-700">
                        <i class="mr-1 text-gray-400 fas fa-pencil-alt fa-fw"></i>Note (Optional - e.g., Transaction
                        ID):
                    </label>
                    <textarea wire:model.defer="notes" id="notes" rows="3"
                        placeholder="Add any relevant details like transaction ID, sender name, etc."
                        class="block w-full px-4 py-3 leading-tight text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                    @error('notes')
                        <p class="flex items-center mt-1 text-xs text-red-600"><i
                                class="mr-1 fas fa-exclamation-circle fa-fw"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" wire:loading.attr="disabled" wire:target="submit"
                        wire:loading.class.remove="hover:from-purple-700 hover:to-indigo-800"
                        wire:loading.class="opacity-75 cursor-wait"
                        class="flex items-center justify-center w-full px-6 py-3 text-base font-bold text-white transition duration-150 ease-in-out border border-transparent rounded-lg shadow-md bg-gradient-to-r from-purple-600 to-indigo-700 hover:from-purple-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                        <span wire:loading.remove wire:target="submit">
                            <i class="mr-2 fas fa-paper-plane fa-fw"></i>
                            @if ($isProUser && $showExtendOption)
                                Submit Manual Extension Request
                            @else
                                Submit Manual Payment Request
                            @endif
                        </span>
                        <span wire:loading wire:target="submit">
                            <i class="mr-2 fas fa-spinner fa-spin"></i>
                            Submitting Request...
                        </span>
                    </button>
                </div>
            </form>
        @else
            <div class="p-6 text-center border border-blue-200 rounded-lg bg-blue-50">
                <i class="mb-4 text-5xl text-blue-500 fas fa-party-popper"></i>
                <h3 class="mb-2 text-lg font-semibold text-blue-800">Your Pro Subscription is Active!</h3>
                <p class="mb-3 text-sm text-blue-700">
                    Enjoy your premium access! You currently have <span
                        class="font-medium">{{ $daysRemaining }}</span> days remaining.
                </p>
                <p class="text-xs text-blue-600">
                    You'll be able to extend your plan manually closer to the expiry date, or use the automatic payment
                    option if available.
                </p>
            </div>
        @endif

        @if (env('APP_DEBUG', false))
            <div class="pt-6 mt-6 text-xs text-gray-400 border-t border-gray-200">
                <p class="font-medium text-gray-500"><i class="mr-1 fas fa-bug"></i>Developer Information:</p>
                <ul class="mt-1 list-disc list-inside">
                    <li>isProUser: {{ $isProUser ? 'true' : 'false' }}</li>
                    <li>showExtendOption: {{ $showExtendOption ? 'true' : 'false' }}</li>
                    <li>hasPendingSubscription: {{ $hasPendingSubscription ? 'true' : 'false' }}</li>
                    <li>pendingSubscriptionCount: {{ $pendingSubscriptionCount }}</li>
                </ul>
            </div>
        @endif

    </div>
</div>
