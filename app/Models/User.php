<?php

namespace App\Models;

use App\Exceptions\NoMoreSlotsToBookException;
use App\Http\Controllers\Booker;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
//    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'grade_id', 'certified_grade_id', 'branch_id', 'title', 'allowed_excuses'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function sessions()
    {
        return $this->belongsToMany(Session::class, 'session_user')
            ->withTimestamps()
            ->withPivot('attendance_status');
    }

    public function didAttend(Session $session): bool
    {
        $session->attendees()->find($this->id)->pivot->refresh();
        return $session->attendees()->find($this->id)->pivot->attendance_status == 'attended';
    }

    public function didntAttend(Session $session): bool
    {
        $session->attendees()->find($this->id)->pivot->refresh();
        return $session->attendees()->find($this->id)->pivot->attendance_status == 'didnt_attend';
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class)->with('learningPoints');
    }

    public function certifiedGrade()
    {
        return $this->belongsTo(Grade::class, 'certified_grade_id')->with('learningPoints');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function canSeeUsers()
    {
        return $this->can('see users');
    }

//    public function searchableAs()
//    {
//        return "users_index";
//    }
//
//    public function toSearchableArray()
//    {
//        return $this->toArray();
//    }
//
//    public function getScoutKey()
//    {
//        return $this->email;
//    }
//
//    public function getScoutKeyName()
//    {
//        return "email";
//    }
//
//    protected function makeAllSearchableUsing($query)
//    {
//        return $query->with(['branches', 'grades', 'certifiedGrades', 'sessions']);
//    }
}
