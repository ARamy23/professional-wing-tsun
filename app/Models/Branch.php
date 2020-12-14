<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    public const NASR_CITY_BRANCH_ID = 1;
    public const MAADI_BRANCH_ID = 2;
    public const FIFTH_SETTLEMENT_BRANCH_ID = 3;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function instructors()
    {
        return $this->hasMany(User::class)->where('is_instructor', true);
    }
}
