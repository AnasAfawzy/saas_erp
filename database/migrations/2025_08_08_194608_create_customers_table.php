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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // CUS-0001
            $table->string('name');
            $table->enum('customer_type', ['individual', 'company'])->default('individual');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('national_id')->nullable();
            $table->string('tax_number')->nullable();

            // Address Information
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Saudi Arabia');

            // Financial Information
            $table->decimal('credit_limit', 15, 2)->default(0);
            $table->enum('payment_terms', ['cash', 'credit'])->default('cash');
            $table->integer('payment_days')->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->decimal('total_purchases', 15, 2)->default(0);

            // Contact Details
            $table->string('contact_person')->nullable();
            $table->string('website')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes
            $table->index(['code', 'is_active']);
            $table->index('phone');
            $table->index('email');
            $table->index('customer_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
