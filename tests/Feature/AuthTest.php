<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use WithFaker;

    public function test_only_admin_can_login()
    {
        Auth::logout();
        $user = User::factory()->create([
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
        ]);

        $this->postJson('/login', [
            'email' => $user->email,
            'password' => 'password'
        ])->assertForbidden();

        Auth::logout();
        $admin = User::factory()->create([
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'is_admin' => 1
        ]);

        $this->postJson('/login', [
            'email' => $admin->email,
            'password' => 'password'
        ])->assertNoContent();
    }
}
