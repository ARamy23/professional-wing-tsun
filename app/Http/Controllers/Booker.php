<?php

namespace App\Http\Controllers;

use App\Events\SomeoneCancelledHisSession;
use App\Exceptions\AlreadyQueuedException;
use App\Exceptions\CancelSessionInSameDayException;
use App\Exceptions\NoMoreSlotsToBookException;
use App\Exceptions\SurpassedAllowedExcusesException;
use App\Exceptions\TryingToBookASessionThatStarted;
use App\Exceptions\TryingToCancelASessionThatStarted;
use App\Models\Session;
use App\Models\SessionUser;
use App\Models\User;
use Carbon\Carbon;

class Booker
{
    // Attendance Statuses = ['booked', 'cancelled', 'didnt_attend', 'attended']);

    static public function queue(Session $session, User $user)
    {
        self::makeSureSessionIsQueueable($session, $user);
        $session->queuingStudents()->attach($user);
        $session->refresh();
    }

    static public function book(Session $sessionToBook, User $user)
    {
        self::makeSureSessionIsBookable($sessionToBook, $user);
        $pivot = SessionUser::where([
            ['session_id', $sessionToBook->id],
            ['user_id', $user->id]
        ])->first();

        if (empty($pivot)) {
            SessionUser::create([
                'attendance_status' => 'booked',
                'user_id' => $user->id,
                'session_id' => $sessionToBook->id
            ]);
        } else {
            $pivot->update([
                'attendance_status' => 'booked'
            ]);
        }
    }

    static public function cancel(Session $sessionToCancel, User $user)
    {
        self::makeSureSessionIsCancellable($sessionToCancel, $user);
        self::handleExcuses($user, $sessionToCancel);
        SessionUser::where([
            ['session_id', $sessionToCancel->id],
            ['user_id', $user->id]
        ])->update([
            'attendance_status' => 'cancelled'
        ]);
        event(new SomeoneCancelledHisSession($sessionToCancel, $user));
    }

    private static function makeSureSessionIsQueueable(Session $session, $user)
    {
        if (in_array($user, $session->queuingStudents->toArray())) throw new AlreadyQueuedException();
    }

    private static function makeSureSessionIsBookable(Session $session, User $user)
    {
        if (SessionManager::activityStateOf($session) != SessionActivityState::NOT_YET_STARTED) throw new TryingToBookASessionThatStarted();
        if ($session->attendees()->where('attendance_status', 'booked')->count() >= $session->limit) throw new NoMoreSlotsToBookException();
    }

    private static function makeSureSessionIsCancellable(Session $session, User $user)
    {
        if (SessionManager::activityStateOf($session) != SessionActivityState::NOT_YET_STARTED) throw new TryingToCancelASessionThatStarted();
        if ($user->allowed_excuses <= 0) throw new SurpassedAllowedExcusesException();

        $sessionIsToday = Carbon::parse($session->from_time)->isToday();
        $sessionBookedToday = Carbon::parse($session->attendees()->find($user->id)->pivot->created_at)->isToday();


        switch ([$sessionIsToday, $sessionBookedToday]) {
            case [true, false]:
                throw new CancelSessionInSameDayException();
            case [false, false]:
            case [false, true]:
            case [true, true]:
                return;
        }
    }

    private static function handleExcuses(User $user, Session $session)
    {

        $sessionIsToday = Carbon::parse($session->from_time)->isToday();
        $sessionBookedToday = Carbon::parse($session->attendees()->find($user->id)->created_at)->isToday();
        switch ([$sessionIsToday, $sessionBookedToday]) {
            case [true, false]:
                return;
            case [true, true]:
            case [false, false]:
                $user->excuses_count++;
                $user->allowed_excuses--;
                break;
            case [false, true]:
                $user->excuses_count++;
                break;
        }

        $user->save();
    }
}
