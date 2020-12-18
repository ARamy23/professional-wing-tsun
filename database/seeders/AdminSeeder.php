<?php

namespace Database\Seeders;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCreator = new CreateNewUser();

        $userCreator->create([
            'name' => 'Ahmed Ramy',
            'email' => 'dev.ahmedramy@gmail.com',
            'grade' => 9,
            'branch' => Branch::find(Branch::NASR_CITY_BRANCH_ID)->name,
            'role' => 'student',
            'password' => 'nba4life',
            'password_confirmation' => 'nba4life'
        ]);

        $userCreator->create([
            'name' => 'Moderator',
            'email' => 'mod@prowingtsun.com',
            'grade' => 12,
            'branch' => Branch::find(Branch::NASR_CITY_BRANCH_ID)->name,
            'password' => 'password',
            'role' => 'admin',
            'password_confirmation' => 'password'
        ]);
    }
}
