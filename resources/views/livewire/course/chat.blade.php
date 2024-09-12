
<div class="p-4 bg-gray-100 border rounded chat-box">
    <div class="h-64 mb-4 overflow-y-auto messages" id="chat-messages">
        @foreach($convo->reverse() as $message) <!-- Reverse the order for display -->
            @if($message['username'] === Auth::user()->name)
                <!-- Your message (right aligned) -->
                <div class="p-2 mb-2 text-right text-white bg-blue-200 rounded-lg message">
                    <div class="inline-block max-w-xs px-4 py-2 text-white break-words bg-blue-200 rounded-lg">
                        <strong>{{ $message['username'] }}:</strong> {{ $message['message'] }}
                        <small class="text-xs text-gray-600 timestamp" data-time="{{ $message['created_at'] }}">
                            {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @else
                <!-- Other's message (left aligned) -->
                <div class="p-2 mb-2 text-left text-black bg-gray-300 rounded-lg message">
                    <div class="inline-block max-w-xs px-4 py-2 text-black break-words bg-gray-300 rounded-lg">
                        <strong>{{ $message['username'] }}:</strong> {{ $message['message'] }}
                        <small class="text-xs text-gray-600 timestamp" data-time="{{ $message['created_at'] }}">
                            {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                        </small>
                    </div>
                </div>
            @endif
        @endforeach
        <!-- Scroll Anchor -->
        <div id="scroll-anchor"></div>
    </div>

    @if($isEnrolled)
        <form wire:submit.prevent="sendMessage" class="mt-4">
            <input type="text" wire:model="message" placeholder="Type your message..." class="w-full p-2 border rounded">
            <button type="submit" class="w-full p-2 mt-2 text-white bg-blue-500 rounded">Send</button>
        </form>
    @else
        <p class="text-red-500">You must be enrolled in this course to participate in the chat.</p>
    @endif
</div>

<script>
    document.addEventListener('livewire:load', function () {
        const messagesDiv = document.getElementById('chat-messages');
        const scrollAnchor = document.getElementById('scroll-anchor');
        
        // Function to scroll to bottom
        function scrollToBottom() {
            scrollAnchor.scrollIntoView({ behavior: 'smooth' });
        }

        // Listen for the Livewire event that triggers auto-scroll
        Livewire.on('message-sent', function() {
            scrollToBottom();
        });

        // Auto-scroll when page loads (for initial render)
        scrollToBottom();
    });
</script>
