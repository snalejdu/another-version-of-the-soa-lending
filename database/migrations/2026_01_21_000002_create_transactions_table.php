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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->string('transaction_number')->unique();
            $table->enum('type', ['disbursement', 'payment']); // disbursement = money given, payment = customer pays
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->decimal('balance_after', 15, 2); // account balance after this transaction
            $table->string('payment_method')->nullable(); // cash, check, bank transfer, etc.
            $table->string('reference_number')->nullable(); // check number, transfer reference, etc.
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users'); // employee who processed it
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
