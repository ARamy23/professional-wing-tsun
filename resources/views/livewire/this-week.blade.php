<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('This Week') }}
    </h2>
</x-slot>

<div>
    <x-jet-dialog-modal wire:model="didBook">
        <x-slot name="title">
            <h1 class="text-center text-3xl text-green-400 font-bold">
                Session Was Booked Successfully!
            </h1>
        </x-slot>


        <x-slot name="content">
            <h2 class="text-center text-2xl text-gray-600 font-bold">
                We can’t wait to meet
                <br>
                You there!
                <br>
                🔥
            </h2>
        </x-slot>

        <x-slot name="footer">

        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="didCancel">
        <x-slot name="title">
            <h1 class="text-center text-3xl text-green-400 font-bold">
                Session Was Cancelled
                <br>
                Successfully!
            </h1>
        </x-slot>

        <x-slot name="content">
            <h2 class="text-center text-2xl text-gray-600 font-bold">
                We hope everything
                <br>
                Is fine at your end
                <br>
                ❤️
            </h2>
        </x-slot>

        <x-slot name="footer">

        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="didQueue">
        <x-slot name="title">
            <h1 class="text-center text-3xl text-green-400 font-bold">
                You were Queued
                <br>
                Successfully!
            </h1>
        </x-slot>

        <x-slot name="content">
            <h2 class="text-center text-2xl text-gray-600 font-bold">
                We will send you an
                <br>
                Email in case if anyone
                <br>
                Decided to cancel
                <br>
                His booking
                <br>
                💪
            </h2>
        </x-slot>

        <x-slot name="footer">

        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="didAlreadyQueue">
        <x-slot name="title">
            <h1 class="text-center text-3xl text-green-400 font-bold">
                You are already
                <br>
                Queued!
            </h1>
        </x-slot>

        <x-slot name="content">
            <h2 class="text-center text-2xl text-gray-600 font-bold">
                Don’t worry we will send
                <br>
                An email in case someone
                <br>
                Decided to cancel
                <br>
                Hang in there!
                <br>
                💪
            </h2>
        </x-slot>

        <x-slot name="footer">

        </x-slot>
    </x-jet-dialog-modal>

    @foreach($days as $day)
        <div
            class="flex flex-col sm:flex-col overflow-auto md:overflow-scroll w-screen object-fill">
            <h2 class='p-4 m-2 w-screen h-max place-self-center bg-blue-400 shadow-blue rounded-2xl text-center text-white font-bold'>
                {{$day->name}}
            </h2>
            <div
                class="flex flex-col sm:flex-row overflow-auto sm:overflow-scroll w-screen object-fill">

                @if($day->is_off_day)
                    <img class='w-full place-self-center rounded-3xl' src="{{asset('images/day-off.png')}}"
                         alt="This is our day-off We know you’re Excited about Wing Tsun But we have Laundry to do"/>
                @else
                    @foreach($day->sessions as $wingchunSession)
                        @livewire('session', ['wingchunSession' => $wingchunSession, 'user' => auth()->user()])
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div>
