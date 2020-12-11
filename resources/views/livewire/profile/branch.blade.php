<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __("Your Branch Information") }}
    </x-slot>

    <x-slot name="description">
        {{ __('Some Information regarding the branch you are assigned to and it\'s address') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-4 sm:col-span-6">
            <h2 class="font-bold bg-red-300 p-4 rounded-full text-3xl text-white text-center">
                Nasr City's Branch
            </h2>

            <p class="font-bold place-self-center text-center p-2">
                {{ __('Instructors') }}
            </p>

            <p class="font-bold text-center">
                Sifu Yasser
                <br>
                Sifu Kareem Mohamed
            </p>

            <p class="font-bold text-center my-4">
                {{ __('Where can you find the branch?') }}
            </p>

            <a href="">

            </a>
        </div>
    </x-slot>
</x-jet-form-section>
