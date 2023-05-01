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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->string('image')->nullable();
            $table->string('description_en')->nullable();
            $table->string('description_ar')->nullable();
            // $table->date('date')->nullable();
            // $table->enum('day', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 29, 30, 31])->nullable();
            // $table->enum('month', [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->nullable();
            // $table->enum('year', [2023, 2024, 2025, 2026, 2027, 2028, 2030])->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            // $table->boolean('join')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
