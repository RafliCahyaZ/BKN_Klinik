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
        Schema::create('medicine_stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('inventory_officer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->foreignId('registration_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out', 'adjustment', 'return', 'expired']);
            $table->integer('quantity');
            $table->integer('beginning_stock');
            $table->integer('last_stock');
            $table->date('expired_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_stock_transactions');
    }
};
