<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    public function allSessions()
    {
        return $this->hasMany(Session::class);
    }

    public function sessions()
    {
        return $this->allSessions()->where('is_private', '=', 0);
    }

    public function privateSessions() {
        return $this->allSessions()->where('is_private', '=', 1);
    }

    static public function currentDay(): Day {
        return Day::firstWhere('date', '=', Carbon::today());
    }
}
