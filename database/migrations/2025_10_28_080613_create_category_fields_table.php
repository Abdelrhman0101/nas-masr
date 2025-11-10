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
        Schema::create('category_fields', function (Blueprint $table) {
            $table->id();
    
            $table->string('category_slug');         
            $table->string('field_name');             
            $table->string('display_name');          
            $table->string('type')->default('string');               
            $table->boolean('required')->default(false);
            $table->boolean('filterable')->default(true);
            $table->json('options')->nullable();                     
            $table->json('rules_json')->nullable();                  
    
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->unique(['category_slug', 'field_name']);

            $table->index(['category_slug', 'is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_fields');
    }
};
