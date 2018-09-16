<?php

use Illuminate\Database\Seeder;
use App\Model\Role;

/**
 * Class RoleTableSeeder
 */
class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_guest = new Role();
        $role_guest->name = 'guest';
        $role_guest->description = 'Regular user';
        $role_guest->save();

        $role_party_maker = new Role();
        $role_party_maker->name = 'party_maker';
        $role_party_maker->description = 'User with privileges to create parties';
        $role_party_maker->save();

        $role_dj = new Role();
        $role_dj->name = 'dj';
        $role_dj->description = 'User with privileges on songs table';
        $role_dj->save();

        $role_admin = new Role();
        $role_admin->name = 'admin';
        $role_admin->description = 'Admin user with all privileges';
        $role_admin->save();
    }
}
