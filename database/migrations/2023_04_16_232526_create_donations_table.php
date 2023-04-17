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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casee_id')->constrained('casees');
            $table->foreignId('donationtype_id')->constrained('donationtypes');
            $table->enum('method',['online_payment','representative','vodafone'])->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->double('amount')->nullable();
            $table->string('amount_description')->nullable();
            $table->string('description')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->date('date_to_send')->nullable();
            $table->bigInteger('user_id');
            $table->enum('status',['pending','accepted']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
