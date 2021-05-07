<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    protected $admin;

    protected function setUp(): void {
        parent::setUp();

        Artisan::call('db:seed');

        $this->admin = User::where('email', 'admin@gmail.com')->first();
    }
}
