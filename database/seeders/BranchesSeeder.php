<?php

namespace Database\Seeders;

use App\Actions\Fortify\CreateNewUser;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Nasr City Branch
        $nasrBranch = Branch::create([
            'name' => 'Nasr City',
            'google_maps_url' => 'https://goo.gl/maps/vmvKL6WEAzxzq5ac7'
        ]);

        $userCreator = new CreateNewUser();

        $nasrBranch->users()->save(
            $userCreator->create([
                'name' => 'Yasser Zaraa',
                'email' => 'sifu.yasser@prowingtsun.com',
                'grade' => 12,
                'is_instructor' => true,
                'branch' => $nasrBranch->name,
                'password' => 'password',
                'password_confirmation' => 'password'
            ]),
            $userCreator->create([
                'name' => 'Kareem Mohsen',
                'email' => 'sifu.kareem@prowingtsun.com',
                'grade' => 12,
                'is_instructor' => true,
                'branch' => $nasrBranch->name,
                'password' => 'password',
                'password_confirmation' => 'password'
            ])
        );

        $maadiBranch = Branch::create([
            'name' => 'Maadi',
            'google_maps_url' => 'https://goo.gl/maps/A2GDX32NC25h7NPAA'
        ]);

        $maadiBranch->users()->save(
            $userCreator->create([
                'name' => 'Ahmed Noah',
                'email' => 'sifu.ahmed.noah@prowingtsun.com',
                'grade' => 12,
                'is_instructor' => true,
                'branch' => $maadiBranch->name,
                'password' => 'password',
                'password_confirmation' => 'password'
            ])
        );

        $fifthSettlementBranch = Branch::create([
            'name' => 'Fifth Settlement',
            'google_maps_url' => 'https://goo.gl/maps/QPs31Nk3EV2kato58'
        ]);

        $fifthSettlementBranch->users()->save(
            $userCreator->create([
                'name' => 'Mohamed Noah',
                'email' => 'sifu.mohamed.noah@prowingtsun.com',
                'grade' => 12,
                'is_instructor' => true,
                'branch' => $fifthSettlementBranch->name,
                'password' => 'password',
                'password_confirmation' => 'password'
            ])
        );
    }
}
