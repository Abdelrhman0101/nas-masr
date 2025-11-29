<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            if (!Schema::hasColumn('listings', 'main_section_id')) {
                $table->foreignId('main_section_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('category_main_sections')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('listings', 'sub_section_id')) {
                $table->foreignId('sub_section_id')
                    ->nullable()
                    ->after('main_section_id')
                    ->constrained('category_sub_section')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            if (Schema::hasColumn('listings', 'sub_section_id')) {
                $table->dropForeign(['sub_section_id']);
                $table->dropColumn('sub_section_id');
            }
            if (Schema::hasColumn('listings', 'main_section_id')) {
                $table->dropForeign(['main_section_id']);
                $table->dropColumn('main_section_id');
            }
        });
    }
};

