<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConsoleTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_create_admin_user()
    {
        $this->artisan('admin:create')
            ->expectsQuestion('Please enter the name', 'Damien')
            ->expectsQuestion('Please enter the email', 'damien@gmail.com')
            ->expectsQuestion('Please enter the password', 'password')
            ->expectsTable(
                [ 'ID', 'Name', 'Email', 'Is Admin' ],
                [
                    [ 1, 'Damien', 'damien@gmail.com', 'Yes' ]
                ]
            )
            ->assertExitCode(0);
    }
}
