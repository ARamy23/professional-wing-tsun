<x-jet-form-section submit="submit">
    <x-slot name="title">
        {{ __("Certified Grade") }}
    </x-slot>

    <x-slot name="description">
        {{ __('Some Information about the grade as well as what to practice at home') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-4 sm:col-span-6">
            <h2 class="font-bold bg-{{ $certifiedGrade->tshirt_color }} p-4 rounded-full shadow text-3xl text-{{ $certifiedGrade->text_color }} text-center">
                {{ $certifiedGrade->chinese_number }}
            </h2>

            <p class="font-bold place-self-center text-center p-2">
                {{ __('Grade ' . $certifiedGrade->english_number) }}
            </p>

            <p class="font-bold my-4">
                {{ $certifiedGrade->info }}
            </p>

            <p class="font-bold my-4">
                {{ __('In this grade you should focus on...') }}
            </p>

            <ul class="list-disc mx-4">
                @foreach($certifiedGrade->learningPoints as $learningPoint)
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

