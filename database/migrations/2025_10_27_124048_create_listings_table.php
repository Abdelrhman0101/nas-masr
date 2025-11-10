<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $t) {
            $t->id();

            $t->unsignedBigInteger('category_id')->index();

            $t->foreignId('user_id')->constrained()->cascadeOnDelete();

            $t->decimal('price', 12, 2)->nullable();
            $t->text('description')->nullable();

            $t->foreignId('governorate_id')->nullable()->constrained()->nullOnDelete();
            $t->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->string('address')->nullable();

            $t->string('main_image')->nullable();
            $t->json('images')->nullable(); // ["path1.jpg","path2.jpg",...]

            $t->enum('status', ['Valid', 'Pending', 'Expired', 'Rejected'])->default('Valid')->index();
            $t->timestamp('published_at')->nullable()->index();
            $t->boolean('admin_approved')->default(true);
            $t->unsignedBigInteger('views')->default(0);
            $t->integer('rank')->default(0);

            $t->timestamps();

            $t->index(['category_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
