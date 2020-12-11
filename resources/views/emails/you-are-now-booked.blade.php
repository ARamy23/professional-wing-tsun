@component('mail::message')
    # You are now booked

    Congrats! Someone cancelled his booking in the session taking place in {{ $readableSessionFromDate }} till {{ $readableSessionToDate }}
    We booked you in his place because you were in the queue!

    See then!

    Stay Safe,<br>
    {{ config('app.name') }}
@endcomponent
