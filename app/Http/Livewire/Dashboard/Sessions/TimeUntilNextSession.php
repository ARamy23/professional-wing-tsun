<?php

namespace App\Http\Livewire\Dashboard\Sessions;

use App\Http\Controllers\SessionStatusForUser;
use App\Models\Session;
use Illuminate\Support\Carbon;
use Livewire\Component;

class TimeUntilNextSession extends Component
{
    public string $diffDate;
    public $session;

    public function mount()
    {
        $this->session = $this->getLatestSessionForCurrentUser();
        $this->updateDiffDate();
    }

    public function render()
    {
        return view('livewire.dashboard.sessions.time-until-next-session');
    }

    public function countDown()
    {
        $this->updateDiffDate();
    }

    /**
     * @return Session|null
     */
    private function getLatestSessionForCurrentUser() {
        return auth()
            ->user()
            ->sessions()
            ->where('attendance_status', '=', 'booked')
            ->orderByDesc('created_at')
            ->first();
    }

    private function updateDiffDate() {
        if ($this->session != null)
            $this->diffDate = Carbon::parse($this->session->from_time)->diffForHumans(['parts' => 3, 'join' => true]);
        else
            $this->diffDate = "You have no upcoming Sessions";
    }
}
