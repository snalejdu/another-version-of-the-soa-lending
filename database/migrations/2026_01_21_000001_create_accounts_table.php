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
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('account_number')->unique();
            $table->decimal('principal_amount', 15, 2);
            $table->decimal('interest_rate', 5, 2); // percentage
            $table->integer('term_months'); // loan term in months
            $table->decimal('monthly_payment', 15, 2);
            $table->decimal('total_amount', 15, 2); // principal + interest
            $table->decimal('balance', 15, 2); // remaining balance
            $table->date('start_date');
            $table->date('maturity_date');
            $table->enum('status', ['active', 'paid', 'overdue', 'defaulted'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
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
