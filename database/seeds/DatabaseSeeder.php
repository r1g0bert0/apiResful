<?php

use Illuminate\Database\Seeder;
use App\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('FabricanteSeeder');
        $this->call('VehiculoSeeder');
        User::truncate();
        $this->call('UserSeeder');
    }
}
