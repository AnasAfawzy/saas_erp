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
        Schema::create('accountable_accounts', function (Blueprint $table) {
            $table->id();

            // العلاقة مع جدول الحسابات
            $table->foreignId('account_id')
                ->constrained('accounts')
                ->onDelete('cascade')
                ->comment('ID الحساب المحاسبي');

            // العلاقة Polymorphic مع الكيانات المختلفة
            $table->string('accountable_type')
                ->comment('نوع الكيان (Customer, Supplier, Warehouse, etc.)');
            $table->unsignedBigInteger('accountable_id')
                ->comment('ID الكيان');

            // معلومات إضافية
            $table->boolean('auto_created')
                ->default(true)
                ->comment('تم إنشاؤه تلقائياً');
            $table->json('sync_settings')
                ->nullable()
                ->comment('إعدادات المزامنة');
            $table->timestamp('last_sync_at')
                ->nullable()
                ->comment('وقت آخر مزامنة');

            $table->timestamps();

            // فهارس للأداء
            $table->unique(['accountable_type', 'accountable_id'], 'unique_entity_account');
            $table->index(['account_id', 'accountable_type'], 'account_entity_index');
            $table->index('auto_created');
            $table->index('last_sync_at');

            // تعليق على الجدول
            $table->comment('جدول الربط المركزي بين الكيانات والحسابات المحاسبية');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountable_accounts');
    }
};
