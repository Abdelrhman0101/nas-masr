<?php

/**
 * Clean database - Keep only admin users
 * Run: php cleanup_users.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\UserClient;
use App\Models\Listing;
use Illuminate\Support\Facades\DB;

echo "\n========================================\n";
echo "   Clean Database (Keep Admin Only)\n";
echo "========================================\n\n";

try {
    DB::beginTransaction();

    // Step 1: Count users
    $totalUsers = User::count();
    $adminUsers = User::where('role', 'admin')->count();
    $nonAdminUsers = User::where('role', '!=', 'admin')->count();

    echo "Current Statistics:\n";
    echo "  - Total Users: {$totalUsers}\n";
    echo "  - Admin Users: {$adminUsers}\n";
    echo "  - Non-Admin Users: {$nonAdminUsers}\n\n";

    if ($nonAdminUsers === 0) {
        echo "✅ No non-admin users to delete!\n";
        DB::rollBack();
        exit(0);
    }

    // Step 2: Get admin user IDs
    $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
    echo "Admin IDs to keep: " . json_encode($adminIds) . "\n\n";

    // Step 3: Delete related data for non-admin users
    echo "Deleting related data...\n";

    // Delete listings
    $deletedListings = Listing::whereNotIn('user_id', $adminIds)->delete();
    echo "  ✓ Deleted {$deletedListings} listings\n";

    // Delete user_clients
    $deletedUserClients = UserClient::whereNotIn('user_id', $adminIds)->delete();
    echo "  ✓ Deleted {$deletedUserClients} user_clients records\n";

    // Delete tokens
    $deletedTokens = DB::table('personal_access_tokens')
        ->whereNotIn('tokenable_id', $adminIds)
        ->where('tokenable_type', 'App\\Models\\User')
        ->delete();
    echo "  ✓ Deleted {$deletedTokens} tokens\n";

    // Step 4: Delete non-admin users
    echo "\nDeleting non-admin users...\n";
    $deletedUsers = User::whereNotIn('id', $adminIds)->delete();
    echo "  ✓ Deleted {$deletedUsers} users\n";

    DB::commit();

    echo "\n========================================\n";
    echo "   ✅ Cleanup Completed Successfully!\n";
    echo "========================================\n\n";

    // Final stats
    $remainingUsers = User::count();
    echo "Remaining Users: {$remainingUsers}\n";
    
    $admins = User::where('role', 'admin')->get();
    foreach ($admins as $admin) {
        echo "  - {$admin->name} (ID: {$admin->id}, Role: {$admin->role})\n";
    }

    echo "\n✅ Database is now clean and ready for fresh data!\n\n";

} catch (Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: " . $e->getMessage() . "\n\n";
    exit(1);
}
