<div>
    <h1 class="text-2xl font-bold">{{ $userToFollow->name }}</h1>
    
    @if($isFollowing)
        <button wire:click="unfollow" class="bg-red-500 text-white px-4 py-2">Unfollow</button>
    @else
        <button wire:click="follow" class="bg-blue-500 text-white px-4 py-2">Follow</button>
    @endif
</div>
