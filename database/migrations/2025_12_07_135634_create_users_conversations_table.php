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
        Schema::create('users_conversations', function (Blueprint $table) {
            $table->id();
            
            // معرف المحادثة الفريد - لربط الرسائل ببعضها
            $table->uuid('conversation_id')->index();
            
            // المرسل (User أو Admin) - Polymorphic Relation
            $table->morphs('sender');
            
            // المستقبل (User أو Admin أو null للرسائل العامة) - Polymorphic Relation
            $table->nullableMorphs('receiver');
            
            // محتوى الرسالة
            $table->text('message');
            
            // وقت القراءة
            $table->timestamp('read_at')->nullable();
            
            // نوع المحادثة: peer (بين عملاء), support (بين عميل وإدارة), broadcast (رسالة عامة)
            $table->string('type', 20)->default('peer')->index();
            
            $table->timestamps();
            
            // للأرشفة والمراقبة بدلاً من الحذف النهائي
            $table->softDeletes();
            
            // Compound Index لتسريع جلب هيستوري المحادثة
            $table->index(['conversation_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_conversations');
    }
};
