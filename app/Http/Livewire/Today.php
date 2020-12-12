<?php

namespace App\Http\Livewire;

use App\Http\Controllers\SessionManager;
use App\Models\Day;
use App\Models\Session;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Today extends Component
{
    public function render()
    {
        return view('livewire.today', [
            'day' => Day::where('date', '=', Carbon::today())->with(['sessions.attendees', 'sessions.instructors'])->get()->first()
        ]);
    }
}
