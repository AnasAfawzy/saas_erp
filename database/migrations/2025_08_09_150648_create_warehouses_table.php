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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique(); // كود المخزن
            $table->string('name'); // اسم المخزن
            $table->enum('type', ['main', 'branch', 'virtual'])->default('main'); // نوع المخزن

            // معلومات الموقع الأساسية
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->default('Saudi Arabia');
            $table->string('phone', 20)->nullable();

            // معلومات المسؤول
            $table->string('manager_name')->nullable();
            $table->string('manager_phone', 20)->nullable();
            $table->string('manager_email')->nullable();

            // ملاحظات
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // فهارس بسيطة
            $table->index(['is_active', 'type']);
            $table->index(['city', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
