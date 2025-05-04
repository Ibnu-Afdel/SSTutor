<div>
    <div class="max-w-2xl p-6 mx-auto space-y-6 bg-white border border-gray-200 shadow-lg sm:p-8 rounded-2xl">

        <div class="mb-4">
            <a href="{{ route('user.profile', ['username' => auth()->user()->username]) }}" {{-- <--- ADJUST ROUTE HERE --}}
                class="inline-flex items-center text-sm font-medium text-gray-500 transition-colors duration-150 hover:text-indigo-600">
                <i class="mr-1 text-gray-400 fas fa-arrow-left fa-fw"></i>
                Back to Subscription Options
            </a>
        </div>

        <h2 class="text-3xl font-bold text-gray-800">
            <i class="mr-2 text-indigo-500 fas fa-hand-holding-usd fa-fw"></i>
            Manual Subscription Request
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

        <div class="p-5 border border-blue-200 rounded-lg bg-blue-50/70">
            <h3 class="mb-3 font-semibold text-gray-800">
                <i class="mr-1 text-blue-500 fas fa-info-circle fa-fw"></i> How to Subscribe Manually:
            </h3>
            <ol class="space-y-2 text-sm text-gray-700 list-decimal list-inside">
                <li>Choose the plan you want below.</li>
                <li>
                    Make a payment of the corresponding amount to:
                    <strong class="block p-2 mt-1 font-medium text-blue-900 bg-blue-100 border border-blue-200 rounded">
                        Bank Name: []<br>
                        Account Name: []<br>
                        Account Number: []<br>
                        Telebirr: []
                    </strong>
                </li>
                <li>Take a clear screenshot of the payment confirmation.</li>
                <li>Upload the screenshot using the form below.</li>
                <li>Optionally add any notes (like the transaction ID).</li>
                <li>Submit your request. We will verify the payment and activate your subscription (usually within 24
                    hours).</li>
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
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 pointer-events-none">
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
                    <i class="mr-1 text-gray-400 fas fa-pencil-alt fa-fw"></i>Note (Optional - e.g., Transaction ID):
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
                        <i class="mr-2 fas fa-paper-plane fa-fw"></i> Submit Manual Payment Request
                    </span>

                    <span wire:loading wire:target="submit">
                        <i class="mr-2 fas fa-spinner fa-spin"></i> Submitting Request...
                    </span>
                </button>
            </div>
        </form>

    </div>
</div>
