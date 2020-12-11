<?php

namespace App\Mail;

use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class YouAreNowBooked extends Mailable
{
    use Queueable, SerializesModels;

    private User $queuingUser;
    private Session $session;

    /**
     * Create a new message instance.
     *
     * @param Session $session
     * @param User $queuingUser
     */
    public function __construct(Session $session, User $queuingUser)
    {
        $this->session = $session;
        $this->queuingUser = $queuingUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $readableSessionFromDate = Carbon::parse($this->session->from_time)->format('l, j, F, g A');
        $readableSessionToDate = Carbon::parse($this->session->to_time)->format('l, j, F, g A');
        return $this->markdown('emails.you-are-now-booked')->with([
            'user' => $this->queuingUser,
            'readableSessionFromDate' => $readableSessionFromDate,
            'readableSessionToDate' => $readableSessionToDate,
        ]);
    }
}
