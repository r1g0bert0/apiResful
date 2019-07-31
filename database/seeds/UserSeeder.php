<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){       
        User::create
        ([
            'email'=>'rigobertotapiacorpa@gmail.com',
            'password'=>Hash::make('123456')
        ]);        
    }
}
