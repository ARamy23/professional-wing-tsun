<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;

class SessionManager
{
    static public function statusForUser(Session $session, User $user)
    {
        if (in_array($user->id, $session->attendees->pluck('id')->toArray()) && $session->willBeAttendedBy($user))
            return SessionStatusForUser::CANCELLABLE;
        else if (in_array($user->id, $session->queuingStudents->pluck('id')->toArray()))
            return SessionStatusForUser::ALREADY_QUEUED;
        else if ($session->attendees->count() >= $session->limit)
            return SessionStatusForUser::QUEUEABLE;
        else
            return SessionStatusForUser::BOOKABLE;
    }

    static public function activityStateOf(Session $session) {
        $from_time = Carbon::parse($session->from_time);
        $now = Carbon::now();
        $to_time = Carbon::parse($session->to_time);

        if ($now->isBefore($from_time))
            return SessionActivityState::NOT_YET_STARTED;
        else if ($now->isBetween($from_time, $to_time))
            return SessionActivityState::ON_GOING;
        else if ($now->isAfter($to_time))
            return SessionActivityState::FINISHED;
    }
}
