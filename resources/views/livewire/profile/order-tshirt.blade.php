<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __("Order T-Shirt") }}
    </x-slot>

    <x-slot name="description">
        {{ __('Here you can order a new T-shirt') }}
    </x-slot>


    <x-slot name="form">
        <div class="col-span-4 sm:col-span-6">
            <h2 class="p-4 my-4 rounded-full bg-green-500 text-white font-bold">
                Order your current grade's shirt
            </h2>
        </div>

        <div>
            <x-jet-dropdown>

                <x-slot name="trigger">
                    <h2 class="p-2 rounded shadow">
                        {{ $tshirt_size }}
                    </h2>
                </x-slot>

                <x-slot name="content">
                    <div class="p-4">
                        <button wire:click="didSelectTShirtSize('{{"T-Shirt Size: Small"}}')">
                            Small
                        </button>

                        <p wire:click="didSelectTShirtSize({{"Small"}})">
                            Medium
                        </p>

                        <p wire:click="didSelectTShirtSize({{"Small"}})">
                            Large
                        </p>

                        <p wire:click="didSelectTShirtSize({{"Small"}})">
                            X-Large
                        </p>

                        <p wire:click="didSelectTShirtSize({{"Small"}})">
                            2X-Large
                        </p>

                        <p wire:click="didSelectTShirtSize({{"Small"}})">
                            3X-Large
                        </p>
                    </div>
                </x-slot>
            </x-jet-dropdown>
        </div>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Successfully Purchased, You will get it on DATE_HERE.') }}
            </x-jet-action-message>

            <x-jet-button wire:loading.attr="disabled" wire:target="photo">
                {{ __('Purchase New Sessions') }}
            </x-jet-button>
        </x-slot>
    </x-slot>
</x-jet-form-section>
