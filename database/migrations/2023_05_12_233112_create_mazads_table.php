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
        Schema::create('mazads', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('description_en');
            $table->string('description_ar');
            $table->integer('starting_price');
            $table->integer('mazad_amount');
            $table->integer('current_price')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'finished'])->default('pending');
            $table->date('end_date');
            $table->time('end_time');
            // $table->integer('vendor_id');
            $table->foreignId('owner_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mazads');
    }
};
