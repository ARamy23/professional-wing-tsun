<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __("Grade Information") }}
    </x-slot>

    <x-slot name="description">
        {{ __('Some Information about the grade as well as what to practice at home') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-4 sm:col-span-6">
            <h2 class="font-bold bg-blue-900 p-4 rounded-full text-3xl text-white text-center">
                九
            </h2>

            <p class="font-bold place-self-center text-center p-2">
                {{ __('Grade 9') }}
            </p>

            <p class="font-bold my-4">
                This Grade sometimes takes from 1 to 2 months to finish
            </p>

            <p class="font-bold my-4">
                {{ __('In this grade you should focus on...') }}
            </p>

            <ul class="list-disc mx-4">
                <li>
                    <p>
                        {{ __('Movement, Move from one leg to the other, switch legs quickly, shifting') }}
                    </p>
                </li>

                <li>
                    {{ __('Chain Punch quickly') }}
                </li>

                <li>
                    {{ __('Elbows Form') }}
                </li>

                <li>
                    {{ __('Stretching') }}
                </li>
            </ul>
        </div>
    </x-slot>
</x-jet-form-section>
