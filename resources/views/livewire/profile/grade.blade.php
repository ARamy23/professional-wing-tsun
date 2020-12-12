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
                {{ $grade->chinese_number }}
            </h2>

            <p class="font-bold place-self-center text-center p-2">
                {{ __('Grade ' . $grade->english_number) }}
            </p>

            <p class="font-bold my-4">
                {{ $grade->info }}
            </p>

            <p class="font-bold my-4">
                {{ __('In this grade you should focus on...') }}
            </p>

            <ul>
                @foreach($grade->learningPoints as $learningPoint)
                    <li>
                        <p>
                            {{ __($learningPoint->point) }}
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
    </x-slot>
</x-jet-form-section>
