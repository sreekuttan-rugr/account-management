<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->foreignUuid('account_id')->constrained('accounts')->onDelete('cascade'); // References accounts table
            $table->enum('type', ['Credit', 'Debit']); // Transaction type
            $table->decimal('amount', 15, 2); // Transaction amount
            $table->text('description')->nullable(); // Optional description
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

