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
        Schema::create('mazad_vendors', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('mazad_id')->nullable();
            // $table->bigInteger('vendor_id')->nullable();
            $table->foreignId('mazad_id')->constrained('mazads');
            $table->foreignId('vendor_id')->constrained('users');
            $table->bigInteger('vendor_paid')->nullable();
            $table->date('vendor_paid_time');
            // $table->enum('mazad_button', ['increment', 'decrement']);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mazad_vendors');
    }
};