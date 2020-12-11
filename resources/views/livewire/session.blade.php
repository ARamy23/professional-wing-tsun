<div>
    <div class="m-4">
        <div class="rounded-2xl border hover:shadow-xl m-2 transition-all object-fill w-full">
            <div>
                <div class="my-2">
                    <p class="text-center">{{ $timeFrame }}</p>
                </div>

                <hr class="bg-opacity-20"/>
            </div>

            <div>
                <div class="my-2">
                    <p class="text-center">Instructors</p>
                </div>

                <hr class="bg-opacity-20"/>
            </div>

            <div>
                <ol class="list-inside list-decimal m-2">
                    @foreach($instructors as $instructor)
                        <li>
                            {{ $instructor->name }}
                        </li>
                    @endforeach
                </ol>
            </div>

            <div>
                <hr class="bg-opacity-20"/>

                <div class="my-2">
                    <p class="text-center">Bookers</p>
                </div>

                <hr class="bg-opacity-20"/>
            </div>

            <div>
                <ol class="list-inside list-decimal m-2">
                    @foreach($bookers as $booker)
                        @if ($wingchunSession->willBeAttendedBy($booker))
                            <li class="{{ $user->id == $booker->id ? "text-green-500" : "text-black" }}">
                                {{ $booker->name }}
                            </li>
                        @endif
                    @endforeach
                </ol>
            </div>

            <div>
                <hr class="bg-opacity-20"/>

                <div class="my-2">
                    <p class="text-center">{{ $sessionActivityState }}</p>
                </div>
            </div>
        </div>

        <div>
            @if ($isActionable)
                <button wire:click="didTapCTAButton"
                        class="bg-{{ $ctaBGColor }}-400 text-white m-2 font-bold py-2 px-4 rounded-2xl object-fill w-full transition-all hover:text-gray-600', hover:bg-{{ $ctaBGColor }}-200">
                    {{ $ctaTitle }}
                </button>
            @endif
        </div>
    </div>
</div>
