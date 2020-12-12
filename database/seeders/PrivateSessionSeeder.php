<?php

namespace Database\Seeders;

use App\Http\Controllers\Booker;
use App\Models\Session;
use App\Models\User;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PrivateSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currentWeek = Week::currentWeek();
        $days = $currentWeek->days()->get();

        $days->each(function ($day) {


            for ($i = 0; $i < $day->private_limit; $i++) {
                $sessionFromTime = Carbon::parse($day->date)->setHour(rand(10, 16));
                $sessionToTime = Carbon::parse($sessionFromTime)->addHours(rand(1, 2));
                if (!$sessionFromTime->isPast()) {
                    $session = Session::create([
                        'from_time' => $sessionFromTime,
                        'to_time' => $sessionToTime,
                        'day_id' => $day->id,
                        'limit' => 1,
                        'is_private' => 1
                    ]);
                    $user = User::factory()->create();
                    Booker::book($session, $user);
                }
            }

        });
    }
}
