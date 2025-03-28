<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // References users table
            $table->string('account_name')->unique(); // Unique account name per user
            $table->string('account_number')->unique(); // Luhn-compliant unique number
            $table->enum('account_type', ['Personal', 'Business']); // Account Type
            $table->enum('currency', ['USD', 'EUR', 'GBP']); // Supported currencies
            $table->decimal('balance', 15, 2)->default(0); // Default balance 0
            $table->timestamps(); // created_at & updated_at
            $table->softDeletes(); // Enables soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};

