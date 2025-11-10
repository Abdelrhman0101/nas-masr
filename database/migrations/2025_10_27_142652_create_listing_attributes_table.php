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
        Schema::create('listing_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained('listings')->cascadeOnDelete();
            $table->string('key', 64)->index();
            $table->enum('type', ['string', 'int', 'decimal', 'bool', 'date', 'json'])->index();
            $table->string('value_string', 255)->nullable()->index();
            $table->bigInteger('value_int')->nullable()->index();
            $table->decimal('value_decimal', 14, 4)->nullable()->index();
            $table->boolean('value_bool')->nullable()->index();
            $table->date('value_date')->nullable()->index();
            $table->json('value_json')->nullable();
            $table->index(['listing_id', 'key']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listing_attributes');
    }
};
