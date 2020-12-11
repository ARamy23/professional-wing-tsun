<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Session;
use App\Models\Week;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WingchunSessionController extends Controller
{
    public function getAllThisWeek(Request $request) {
        $currentUser = Auth::user();
        $currentSessionsThisWeek = Week::currentWeek()->days()->with(['sessions.attendees', 'sessions.instructors'])->get();
        $currentSessionsThisWeek->each(function (Day $day) {
            $day->sessions->each(function (Session $session) {
                $session->statusToUser = 'bookable';
                $session->state = 'Not yet Started';
            });
        });

        return view('livewire.session', ['user' => $currentUser, 'session' => $currentSessionsThisWeek->first()]);
    }
}
