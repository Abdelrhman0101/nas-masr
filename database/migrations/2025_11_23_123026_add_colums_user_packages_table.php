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
        Schema::table('user_packages', function (Blueprint $table) {
            $table->integer('featured_days')->default(0)->after('featured_ads_used');
            $table->timestamp('featured_start_date')->nullable()->after('featured_days');
            $table->timestamp('featured_expire_date')->nullable()->after('featured_start_date');

            // Standard plan period
            $table->integer('standard_days')->default(0)->after('standard_ads_used');
            $table->timestamp('standard_start_date')->nullable()->after('standard_days');
            $table->timestamp('standard_expire_date')->nullable()->after('standard_start_date');
        });

        DB::statement("
            UPDATE user_packages
            SET
                featured_days          = COALESCE(days, 0),
                featured_start_date    = start_date,
                featured_expire_date   = expire_date,
                standard_days          = COALESCE(days, 0),
                standard_start_date    = start_date,
                standard_expire_date   = expire_date
        ");
    }

    public function down(): void
    {
        Schema::table('user_packages', function (Blueprint $table) {
            $table->dropColumn([
                'featured_days',
                'featured_start_date',
                'featured_expire_date',
                'standard_days',
                'standard_start_date',
                'standard_expire_date',
            ]);
        });
    }
};
