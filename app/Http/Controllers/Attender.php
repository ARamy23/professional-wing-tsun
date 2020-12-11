<?php

namespace App\Http\Controllers;

use App\Exceptions\AttendingSessionAfterItHasEndedException;
use App\Exceptions\AttendingSessionBeforeItsTimeException;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;

class Attender
{
    const BEFORE_SESSION_ATTENDANCE_BUFFER_TIME = 15; // Minutes
    const AFTER_SESSION_ATTENDANCE_BUFFER_TIME = 15; // Minutes

    static public function attend(User $user, Session $session)
    {
        self::makeSureSessionIsAttendable($session);
        $pivot = $session->attendees()->find($user->id)->pivot;
        $pivot->attendance_status = 'attended';
        $pivot->save();
        $user->refresh();
        $session->refresh();
    }

    static private function makeSureSessionIsAttendable(Session $session) {
        $from_time = Carbon::parse($session->from_time);
        $now = Carbon::now();
        $to_time = Carbon::parse($session->to_time);

        if ($now->lessThan($from_time->subMinutes(Attender::BEFORE_SESSION_ATTENDANCE_BUFFER_TIME))) {
            throw new AttendingSessionBeforeItsTimeException();
        } else if ($to_time->addMinutes(Attender::AFTER_SESSION_ATTENDANCE_BUFFER_TIME)->lessThan($now)) {
            throw new AttendingSessionAfterItHasEndedException();
        }
    }
}
