<div>
    <x-jet-dropdown align="right" width="48">
        <x-slot name="trigger">
            <input for="branch" id="branch" name="branch" wire:model="branch" placeholder="Select a branch..." class="form-input rounded-md shadow-sm block mt-1 w-full" type="text" autocomplete="off">
        </x-slot>

        <x-slot name="content">
            @foreach($branches as $branch)
                <span wire:click="select({{ $branch->id }})" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                        {{ $branch->name }}
                </span>
            @endforeach
        </x-slot>
    </x-jet-dropdown>
</div>
