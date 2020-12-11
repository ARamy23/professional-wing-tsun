<?php

namespace App\Http\Livewire;

use App\Models\Day;
use App\Models\Session;
use App\Models\User;
use App\Models\Week;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ThisWeek extends Component
{
    public $days;
    public $didBook = false;
    public $didCancel = false;
    public $didQueue = false;
    public $didAlreadyQueue = false;

    protected $listeners = [
        'bookModal',
        'queueModal',
        'cancelModal',
        'alreadyQueuedModal'
    ];

    public function bookModal()
    {
        $this->didBook = true;
    }

    public function queueModal()
    {
        $this->didQueue = true;
    }

    public function cancelModal()
    {
        $this->didCancel = true;
    }

    public function alreadyQueuedModal()
    {
        $this->didAlreadyQueue = true;
    }

    public function render()
    {
        $this->days = Week::currentWeek()->days()->with(['sessions.attendees', 'sessions.instructors'])->get();
        return view('livewire.this-week');
    }
}
