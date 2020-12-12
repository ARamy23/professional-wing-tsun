<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __("Subscription Information") }}
    </x-slot>

    <x-slot name="description">
        {{ __('Information about your subscription and a section to pay for more sessions') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-4 sm:col-span-6">
            <h2 class="p-4 my-4 rounded-full bg-green-500 text-white font-bold">
                5 Sessions left in your balance
            </h2>

            <x-slot name="actions">
                <x-jet-action-message class="mr-3" on="saved">
                    {{ __('Successfully Purchased.') }}
                </x-jet-action-message>

                <x-jet-button wire:loading.attr="disabled" wire:target="photo">
                    {{ __('Confirm Purchase') }}
                </x-jet-button>
            </x-slot>
        </div>
    </x-slot>
</x-jet-form-section>
