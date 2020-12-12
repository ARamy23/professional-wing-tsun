<?php

namespace App\Http\Controllers;

class SessionStatusForUser
{
    const CANCELLABLE = "cancellable";
    const ALREADY_QUEUED = "alreadyQueued";
    const QUEUEABLE = "queueable";
    const BOOKABLE = "bookable";
    const UNACTIONABLE = "unactionable";
}
