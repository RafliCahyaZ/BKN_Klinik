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
        Schema::create('login_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->string('username_attempt', 255);
            $table->string('device_info')->nullable();
            $table->timestamp('login_at')->useCurrent();
            $table->boolean('is_success')->default(false);
            $table->string('failure_reason')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('ip_address');
            $table->index(['is_success', 'login_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_logs');
    }
};
