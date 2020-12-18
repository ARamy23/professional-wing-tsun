<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'see users']);
        Permission::create(['name' => 'attend sessions']);
        Permission::create(['name' => 'instruct sessions']);

        // create roles and assign created permissions

        // this can be done as separate statements
        $instructorRole = Role::create(['name' => 'instructor', 'title' => 'Sifu']);
        $studentRole = Role::create(['name' => 'student', 'title' => 'Student']);
        $moderatorRole = Role::create(['name' => 'moderator', 'title' => 'Moderator']);
        $adminRole = Role::create(['name' => 'admin', 'title' => 'Admin']);

        $instructorRole->givePermissionTo(['see users', 'instruct sessions']);
        $studentRole->givePermissionTo('attend sessions');
        $moderatorRole->givePermissionTo('see users');
        $adminRole->givePermissionTo(Permission::all());
    }
}
