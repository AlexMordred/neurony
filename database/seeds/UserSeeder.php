<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an admin
        User::forceCreate([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('qwerty'),
            'is_admin' => 1,
        ]);
    }
}
