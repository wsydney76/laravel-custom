<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class CreateFirstUser extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'app:create-first-user
                            {name : The user\'s name}
                            {password : The user\'s password}
                            {email : The user\'s email (Used for login)}';

    /**
     * The console command description.
     */
    protected $description = 'Creates the first user if no users exist';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (User::exists()) {
            $this->error('A user already exists. Aborting.');

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
        ]);

        $this->info("First user '{$user->email}' created successfully.");

        return self::SUCCESS;
    }
}
