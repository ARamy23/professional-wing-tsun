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
            'branch' => Branch::NASR_CITY_BRANCH_ID,
            'password' => 'nba4life',
            'password_confirmation' => 'nba4life'
        ]);
    }
}
