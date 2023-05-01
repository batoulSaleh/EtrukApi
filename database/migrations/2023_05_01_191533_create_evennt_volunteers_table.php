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
        Schema::create('evennt_volunteers', function (Blueprint $table) {
            $table->id();
            $table->boolean('joined')->default(false);
            // $table->foreignId('event_id')->constrained('events');
            // $table->foreignId('volunteer_id')->constrained('volunteers');
            $table->bigInteger('event_id')->nullable();
            $table->bigInteger('volunteer_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_volunteers');
    }
};
