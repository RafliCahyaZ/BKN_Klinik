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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->text('fullname');
            $table->enum('gender', ['male, female']);
            $table->text('address');
            $table->dateTime('date_of_birth');
            $table->string('telp_number', 13)->unique();
            $table->string('history', 100);
            $table->integer('medical_record_number')->unique();
            $table->string('medical_record_file_url', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
