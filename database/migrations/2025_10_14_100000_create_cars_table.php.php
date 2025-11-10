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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('add_category')->default('Car');
            $table->enum('add_status', ['Valid', 'Pending', 'Expired', 'Rejected'])->default('Valid');
            $table->boolean('admin_approved')->default(true);
            $table->unsignedBigInteger('views')->default(0);
            $table->integer('rank')->default(0);


            $table->foreignId('governorate_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('make_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('model_id')->nullable()->constrained()->nullOnDelete();

            $table->year('year')->nullable();
            $table->integer('kilometers')->nullable();
            $table->string('type')->nullable();
            $table->string('color')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('transmission')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('contact_phone');
            $table->string('whatsapp_phone')->nullable();
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->string('main_image')->nullable();

            // == Plan Info ==
            $table->string('plan_type')->nullable();
            $table->integer('plan_days')->nullable();
            $table->timestamp('plan_expires_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
