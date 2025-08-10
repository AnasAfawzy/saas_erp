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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique()->comment('كود الحساب');
            $table->string('name')->comment('اسم الحساب بالعربية');
            $table->string('name_en')->nullable()->comment('اسم الحساب بالإنجليزية');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('الحساب الأب');
            $table->enum('account_type', [
                'assets',
                'liabilities',
                'equity',
                'revenue',
                'expenses'
            ])->nullable()->comment('نوع الحساب');
            $table->enum('account_nature', [
                'debit',
                'credit',
                'both'
            ])->nullable()->comment('طبيعة الحساب');
            $table->enum('account_level_type', [
                'title',     // عنوان
                'account',   // حساب
                'sub_account' // حساب فرعي
            ])->default('account')->comment('نوع مستوى الحساب');
            $table->integer('level')->default(1)->comment('مستوى الحساب في الشجرة');
            $table->boolean('is_active')->default(true)->comment('نشط/غير نشط');
            $table->boolean('has_children')->default(false)->comment('له حسابات فرعية');
            $table->text('description')->nullable()->comment('وصف الحساب');
            $table->decimal('balance', 15, 2)->default(0)->comment('الرصيد الحالي');
            $table->integer('sort_order')->default(0)->comment('ترتيب العرض');
            $table->timestamps();

            // Foreign Keys
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('cascade');

            // Indexes
            $table->index(['account_type', 'is_active']);
            $table->index(['parent_id', 'sort_order']);
            $table->index('level');
            $table->index('account_level_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
