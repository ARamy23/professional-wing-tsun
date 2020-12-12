<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __("Certified Grade") }}
    </x-slot>

    <x-slot name="description">
        {{ __('Some Information about the grade as well as what to practice at home') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-4 sm:col-span-6">
            <h2 class="font-bold bg-white shadow p-4 rounded-full text-3xl text-black text-center">
                一
            </h2>

            <p class="font-bold place-self-center text-center p-2">
                {{ __('Grade 1') }}
            </p>

            <p class="font-bold my-4">
                {{ __('In this grade you should focus on...') }}
            </p>

            <ul class="list-disc mx-4">
                <li>
                    {{ __('Movement') }}
                </li>

                <li>
                    {{ __('Chain Punch Form') }}
                </li>
            </ul>
        </div>
    </x-slot>
</x-jet-form-section>

