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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('code')->unique()->comment('كود المورد');
            $table->string('name')->comment('اسم المورد');
            $table->enum('supplier_type', ['individual', 'company'])->default('individual')->comment('نوع المورد');
            $table->string('phone')->unique()->comment('رقم الهاتف');
            $table->string('email')->nullable()->comment('البريد الإلكتروني');
            $table->string('national_id')->nullable()->comment('رقم الهوية');
            $table->string('tax_number')->nullable()->comment('الرقم الضريبي');
            $table->boolean('is_active')->default(true)->comment('حالة النشاط');

            // Address Information
            $table->text('address')->nullable()->comment('العنوان');
            $table->string('city')->nullable()->comment('المدينة');
            $table->string('state')->nullable()->comment('المنطقة');
            $table->string('postal_code')->nullable()->comment('الرمز البريدي');
            $table->string('country')->nullable()->default('Saudi Arabia')->comment('الدولة');

            // Financial Information
            $table->decimal('credit_limit', 15, 2)->default(0)->comment('الحد الائتماني');
            $table->decimal('current_balance', 15, 2)->default(0)->comment('الرصيد الحالي');
            $table->enum('payment_terms', ['cash', 'credit', 'installment'])->default('cash')->comment('شروط الدفع');
            $table->integer('payment_days')->default(0)->comment('أيام السداد');
            $table->decimal('discount_percentage', 5, 2)->default(0)->comment('نسبة الخصم المستلم');

            // Contact Information
            $table->string('contact_person')->nullable()->comment('شخص الاتصال');
            $table->string('website')->nullable()->comment('الموقع الإلكتروني');
            $table->string('whatsapp')->nullable()->comment('واتساب');
            $table->text('notes')->nullable()->comment('الملاحظات');

            // Rating Information
            $table->decimal('rating', 2, 1)->default(0)->comment('التقييم العام');
            $table->decimal('delivery_rating', 2, 1)->default(0)->comment('تقييم التوصيل');
            $table->decimal('quality_rating', 2, 1)->default(0)->comment('تقييم الجودة');
            $table->date('last_supply_date')->nullable()->comment('تاريخ آخر توريد');

            $table->timestamps();

            // Indexes for better performance
            $table->index(['is_active']);
            $table->index(['supplier_type']);
            $table->index(['city']);
            $table->index(['payment_terms']);
            $table->index(['rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
