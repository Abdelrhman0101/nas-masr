<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:fix-role {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix admin role for users who were mistakenly changed to advertiser';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if ($email) {
            // Fix specific user by email
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("User with email {$email} not found!");
                return 1;
            }

            $oldRole = $user->role;
            $user->role = 'admin';
            $user->save();

            $this->info("User {$user->email} role changed from '{$oldRole}' to 'admin'");
            return 0;
        }

        // If no email provided, ask for confirmation to fix all known admin emails
        $adminEmails = [
            'admin@example.com',
            // Add any other known admin emails here
        ];

        $this->warn('Will restore admin role for the following users:');
        foreach ($adminEmails as $adminEmail) {
            $this->line("  - {$adminEmail}");
        }

        if (!$this->confirm('Do you want to continue?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $fixed = 0;
        foreach ($adminEmails as $adminEmail) {
            $user = User::where('email', $adminEmail)->first();
            
            if ($user) {
                $oldRole = $user->role;
                $user->role = 'admin';
                $user->save();
                
                $this->info("✓ Fixed {$user->email} (was: {$oldRole})");
                $fixed++;
            } else {
                $this->warn("✗ User {$adminEmail} not found");
            }
        }

        $this->info("\nFixed {$fixed} admin user(s).");
        return 0;
    }
}
