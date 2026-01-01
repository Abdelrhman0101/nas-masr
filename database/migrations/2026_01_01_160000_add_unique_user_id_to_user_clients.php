<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Clean up duplicate records before adding unique constraint
        $this->cleanupDuplicates();

        // Step 2: Add unique constraint on user_id
        Schema::table('user_clients', function (Blueprint $table) {
            $table->unique('user_id');
        });
    }

    /**
     * Clean up duplicate user_clients records by merging clients arrays
     */
    private function cleanupDuplicates(): void
    {
        // Find duplicate user_ids
        $duplicates = DB::table('user_clients')
            ->select('user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('user_id')
            ->having('count', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $userId = $duplicate->user_id;

            // Get all records for this user_id
            $records = DB::table('user_clients')
                ->where('user_id', $userId)
                ->orderBy('id')
                ->get();

            if ($records->count() <= 1) {
                continue;
            }

            // Merge all clients arrays
            $mergedClients = [];
            foreach ($records as $record) {
                $clients = json_decode($record->clients, true) ?? [];
                $mergedClients = array_merge($mergedClients, $clients);
            }

            // Remove duplicates and re-index
            $mergedClients = array_values(array_unique($mergedClients));

            // Keep the first record and update it
            $keepRecord = $records->first();
            DB::table('user_clients')
                ->where('id', $keepRecord->id)
                ->update([
                    'clients' => json_encode($mergedClients),
                    'updated_at' => now(),
                ]);

            // Delete other duplicate records
            DB::table('user_clients')
                ->where('user_id', $userId)
                ->where('id', '!=', $keepRecord->id)
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_clients', function (Blueprint $table) {
            $table->dropUnique(['user_id']);
        });
    }
};
