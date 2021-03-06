<?php

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
         $this->call(UserTableSeeder::class);
         $this->call(SongTableSeeder::class);
         $this->call(PartyTableSeeder::class);
    }
}
