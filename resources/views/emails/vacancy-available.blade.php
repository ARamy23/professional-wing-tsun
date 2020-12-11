@component('mail::message')
    # Vacancy Available

    {{ $user->name }} Cancelled the session taking place in {{ $readableSessionFromDate }} till {{ $readableSessionToDate }}, you can take his place by pressing
    the button below

    @component('mail::button', ['url' => url('/sessions'.$session->id.'/book')])
        Book his place.
    @endcomponent

    Stay safe,<br>
    {{ config('app.name') }}
@endcomponent
