<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create (or update) the admin user from environment variables.
     *
     * Configure ADMIN_NAME, ADMIN_EMAIL and ADMIN_PASSWORD as environment
     * variables on the target environment before running this seeder —
     * never commit real credentials to the repository.
     */
    public function run(): void
    {
        $email = config('portfolio.admin.email');
        $password = config('portfolio.admin.password');

        if (! $email || ! $password) {
            $this->command->error('Set ADMIN_EMAIL and ADMIN_PASSWORD environment variables before running this seeder.');

            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => config('portfolio.admin.name'),
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info("Admin user ready: {$email}");
    }
}
