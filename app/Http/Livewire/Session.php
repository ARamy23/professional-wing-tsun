<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Booker;
use App\Http\Controllers\SessionActivityState;
use App\Http\Controllers\SessionManager;
use App\Http\Controllers\SessionStatusForUser;
use App\Models\User;
use App\Models\Session as WingchunSession;
use Livewire\Component;

class Session extends Component
{
    // View Attributes
    public string $timeFrame;
    public $instructors;

    public $bookers;
    public string $sessionActivityState;
    public string $sessionAvailabilityStateForCurrentUser;
    public string $ctaTitle;
    public string $ctaBGColor;
    public bool $isActionable;

    public WingchunSession $wingchunSession;
    public User $user;

    public function mount(User $user, WingchunSession $wingchunSession)
    {
        $this->wingchunSession = $wingchunSession;
        $this->user = $user;
        $this->timeFrame = $this->getTimeFrame($wingchunSession);
        $this->instructors = $wingchunSession->instructors;
        $this->bookers = $wingchunSession->attendees;
    }

    private function getTimeFrame(WingchunSession $session)
    {
        $readableFromTimeHour = $session->readableFromTime();
        $readableToTimeHour = $session->readableToTime();
        return "$readableFromTimeHour - $readableToTimeHour";
    }

    public function didTapCTAButton()
    {
        switch ($this->sessionAvailabilityStateForCurrentUser) {
            case "bookable":
                Booker::book($this->wingchunSession, $this->user);
                $this->render();
                $this->emit('bookModal');
                break;
            case "cancellable":
                Booker::cancel($this->wingchunSession, $this->user);
                $this->render();
                $this->emit('cancelModal');
                break;
            case "queueable":
                Booker::queue($this->wingchunSession, $this->user);
                $this->render();
                $this->emit('queueModal');
                break;
            case "already_queued":
                $this->render();
                $this->emit('alreadyQueuedModal');
                break;
        }
        $this->wingchunSession->refresh();
        $this->user->refresh();
    }

    public function getCTAButtonTitle()
    {
        switch ($this->sessionAvailabilityStateForCurrentUser) {
            case SessionStatusForUser::BOOKABLE:
                return "Book";
            case SessionStatusForUser::CANCELLABLE:
                return "Cancel";
            case SessionStatusForUser::QUEUEABLE:
                return "Queue";
            case SessionStatusForUser::ALREADY_QUEUED:
                return "Already Queued.";
            case SessionStatusForUser::UNACTIONABLE:
                return "";
        }
    }

    public function getCTAButtonBGColor()
    {
        switch ($this->sessionAvailabilityStateForCurrentUser) {
            case SessionStatusForUser::BOOKABLE:
                return "green";
            case SessionStatusForUser::CANCELLABLE:
                return "red";
            case SessionStatusForUser::QUEUEABLE:
            case SessionStatusForUser::ALREADY_QUEUED:
                return "orange";
            case SessionStatusForUser::UNACTIONABLE:
                return "white";
        }
    }

    public function getIsActionable() {
        if ($this->sessionAvailabilityStateForCurrentUser == SessionStatusForUser::UNACTIONABLE)
            return false;
        switch ($this->sessionActivityState) {
            case SessionActivityState::NOT_YET_STARTED:
                return true;
            case SessionActivityState::FINISHED:
            case SessionActivityState::ON_GOING:
                return false;
        }
    }

    public function render()
    {
        $this->sessionActivityState = SessionManager::activityStateOf($this->wingchunSession);
        $this->sessionAvailabilityStateForCurrentUser = SessionManager::statusForUser($this->wingchunSession, $this->user);
        $this->isActionable = $this->getIsActionable();
        $this->ctaTitle = $this->getCTAButtonTitle();
        $this->ctaBGColor = $this->getCTAButtonBGColor();
        return view('livewire.session');
    }
}
