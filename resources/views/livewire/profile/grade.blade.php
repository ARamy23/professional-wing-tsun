<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __("Grade Information") }}
    </x-slot>

    <x-slot name="description">
        {{ __('Some Information about the grade as well as what to practice at home') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-4 sm:col-span-6">
            <h2 class="font-bold bg-{{ $grade->tshirt_color }} p-4 rounded-full shadow text-3xl text-{{ $grade->text_color }} text-center">
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

            <ul class="list-disc mx-4">
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
