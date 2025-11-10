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
            if (Schema::hasColumn('listings', 'category_id')) {
                $table->foreign('category_id')
                    ->references('id')
                    ->on('categories')
                    ->cascadeOnDelete();
            }
            if (!Schema::hasColumn('listings', 'contact_phone')) {
                $table->string('contact_phone', 20)->nullable()->after('address');
            }
            if (!Schema::hasColumn('listings', 'whatsapp_phone')) {
                $table->string('whatsapp_phone', 20)->nullable()->after('contact_phone');
            }
            $table->foreignId('make_id')->nullable()->constrained('makes')->nullOnDelete();
            $table->foreignId('model_id')->nullable()->constrained('models')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            if (Schema::hasColumn('listings', 'whatsapp_phone')) {
                $table->dropColumn('whatsapp_phone');
            }
            if (Schema::hasColumn('listings', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
            $table->dropForeign(['make_id']);
            $table->dropForeign(['model_id']);
        });
    }
};
