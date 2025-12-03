<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('category_plan_prices', function (Blueprint $table) {
            $table->decimal('featured_ad_price', 10, 2)
                ->default(0)
                ->after('price_featured');

            $table->unsignedInteger('featured_days')
                ->default(0)
                ->after('featured_ad_price');

            $table->decimal('standard_ad_price', 10, 2)
                ->default(0)
                ->after('price_standard');

            $table->unsignedInteger('standard_days')
                ->default(0)
                ->after('standard_ad_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_plan_prices', function (Blueprint $table) {
            $table->dropColumn([
                'featured_ad_price',
                'featured_days',
                'standard_ad_price',
                'standard_days',
            ]);
        });
    }
};
