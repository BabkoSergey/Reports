<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Rate;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(UserTableSeeder::class);
    }

}

class UserTableSeeder extends Seeder {

    public function run() {
        DB::table('users')->delete();

        User::create([
            'email' => 'superadmin@urich.dev',
            'name' => 'superadmin',
            'password' => Hash::make('qwe01234q'),
        ]);
        
        User::create([
            'email' => 'admin@urich.dev',
            'name' => 'admin',
            'password' => Hash::make('Qwerty123'),
        ]);

        $this->command->info('Create superadmin and admin User');
    }

}