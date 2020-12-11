<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;
    protected $table = 'wingchun_sessions';

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'session_user')
            ->withTimestamps()
            ->withPivot('attendance_status');
    }

    public function instructors()
    {
        return $this->belongsToMany(User::class, 'instructor_session')
            ->withTimestamps();
    }

    public function queuingStudents()
    {
        return $this->belongsToMany(User::class, 'session_queuing_user')
            ->withTimestamps();
    }

    public function willBeAttendedBy(User $user) {
        return $this->attendees()->firstWhere([['user_id', '=' , $user->id], ['attendance_status', '!=', 'cancelled']]) != null;
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }

    public function bookingURI(): String
    {
        return "/api/sessions/$this->id/book";
    }

    public function readableFromTime() {
        return Carbon::parse($this->from_time)->format('g:i A');
    }

    public function readableToTime() {
        return Carbon::parse($this->to_time)->format('g:i A');
    }
}
