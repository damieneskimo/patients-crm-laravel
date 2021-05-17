<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('Please enter the name');
        $email = $this->ask('Please enter the email');
        $password = $this->secret('Please enter the password');

        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'email_verified_at' => now(),
            'is_admin' => 1
        ]);

        $this->table(
            [ 'ID', 'Name', 'Email', 'Is Admin' ],
            [
                [ $admin->id, $name, $email, $admin->is_admin? 'Yes' : 'No' ]
            ]
        );
    }
}
