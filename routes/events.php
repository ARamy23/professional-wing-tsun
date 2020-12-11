<?php

use App\Events\SomeoneCancelledHisSession;
use App\Http\Controllers\Booker;
use App\Mail\VacancyAvailableMail;
use App\Mail\YouAreNowBooked;
use App\Models\User;
use Carbon\Carbon;
use \Illuminate\Support\Facades\Event;
use function Illuminate\Events\queueable;
use Illuminate\Support\Facades\Mail;

Event::listen(queueable(function (SomeoneCancelledHisSession $event) {
    if (count($event->session->queuingStudents) == 0) {
        $usersToNotify = User::whereNotIn('id', $event->session->attendees->pluck('id'))
            ->where('id', '<>', $event->user->id)
            ->get();
        $usersToNotify->each(function ($user) use ($event) {
            Mail::to($user->email)
                ->send(new VacancyAvailableMail($event->session, $user));
        });
    } else {
        $firstStudentInQueue = $event->session->queuingStudents->first();
        $event->session->queuingStudents()->detach($firstStudentInQueue);
        Booker::book($event->session, $firstStudentInQueue);
        Mail::to($firstStudentInQueue->email)
            ->send(new YouAreNowBooked($event->session, $firstStudentInQueue));
    }
}));

