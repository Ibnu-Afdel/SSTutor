<div class="flex flex-col h-screen p-4 bg-gray-100 md:max-w-2xl md:mx-auto">
    <div class="flex-1 p-2 overflow-y-auto bg-white border rounded shadow messages" id="chat-messages">
        @foreach ($convo as $message)
            @php
                $isMe = $message['username'] === Auth::user()->name;
            @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }} mb-2">
                <div
                    class="max-w-xs p-2 text-sm rounded-lg {{ $isMe ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                    <strong>{{ $message['username'] }}</strong>: {{ $message['message'] }}
                    <div class="mt-1 text-xs {{ $isMe ? 'text-gray-200' : 'text-gray-600' }}">
                        {{ \Carbon\Carbon::parse($message['created_at'])->diffForHumans() }}
                    </div>
                </div>
            </div>
        @endforeach
        <div id="scroll-anchor"></div>
    </div>

    @if ($isEnrolled)
        <form wire:submit.prevent="sendMessage" class="mt-4 space-y-2">
            <input type="text" wire:model="message" placeholder="Type your message..."
                class="w-full p-3 text-sm border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            <button type="submit"
                class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Send</button>
        </form>
    @else
        <p class="p-4 text-center text-red-500 bg-white border border-red-300 rounded">
            You must be enrolled in this course to participate in the chat.
        </p>
    @endif
</div>

<script>
    document.addEventListener('livewire:load', function() {
        const scrollAnchor = document.getElementById('scroll-anchor');

        function scrollToBottom() {
            scrollAnchor?.scrollIntoView({
                behavior: 'smooth'
            });
        }

        scrollToBottom();

        Livewire.hook('message.processed', (message, component) => {
            scrollToBottom();
        });

        Livewire.on('message-sent', scrollToBottom);
    });
</script>
