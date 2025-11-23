<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->date('dt');

            $table->foreignUuid('user_from_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->foreignUuid('user_to_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('text', 50);
            $table->decimal('amount', 18, 2)->check('amount > 0');

            $table->char('operation_type', 1);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
