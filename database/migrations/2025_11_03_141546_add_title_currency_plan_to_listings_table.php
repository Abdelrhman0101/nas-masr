<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            if (!Schema::hasColumn('listings', 'title')) {
                $table->string('title', 180)->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('listings', 'currency')) {
                $table->string('currency', 10)->default('EGP')->after('price');
            }

            if (!Schema::hasColumn('listings', 'plan_type')) {
                $table->string('plan_type')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn(['title', 'currency', 'plan_type']);
        });
    }
};
