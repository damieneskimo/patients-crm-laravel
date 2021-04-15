<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'mobile' => '123456',
            'gender' => 'male',
            'email_verified_at' => now(),
            'is_admin' => 1,
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);

        \App\Models\User::factory(50)->hasNotes(3)->create();

    }
}
