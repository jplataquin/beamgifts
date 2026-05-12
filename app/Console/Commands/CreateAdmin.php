<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name?} {email?} {password?}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('Enter admin name');
        $email = $this->argument('email') ?? $this->ask('Enter admin email');
        $password = $this->argument('password') ?? $this->secret('Enter admin password');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admins,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->error('Admin creation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("- $error");
            }
            return 1;
        }

        Admin::create([
            'name' => $name,
            'email' => $email,
            'password' => $password, // Automatically hashed via model cast
        ]);

        $this->info("Admin '{$name}' <{$email}> created successfully.");
        return 0;
    }
}
