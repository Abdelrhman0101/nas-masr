<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_plan_subscriptions', function (Blueprint $table) {
            $table->string('payment_method', 50)
                ->nullable()
                ->after('payment_status'); 
        });

        Schema::table('listing_payments', function (Blueprint $table) {
            $table->string('payment_method', 50)
                ->nullable()
                ->after('amount'); 
        });
    }

    public function down(): void
    {
        Schema::table('user_plan_subscriptions', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });

        Schema::table('listing_payments', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
};
