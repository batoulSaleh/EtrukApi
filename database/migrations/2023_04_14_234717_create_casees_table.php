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
        Schema::create('casees', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('type_en')->nullable();
            $table->text('type_ar')->nullable();
            $table->text('gender_en')->nullable();
            $table->text('gender_ar')->nullable();
            $table->text('reason_reject_en')->nullable();
            $table->text('reason_reject_ar')->nullable();
            $table->string('file')->nullable();
            $table->foreignId('donationtype_id')->constrained('donationtypes');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('user_id')->constrained('users');
            $table->double('initial_amount');
            $table->double('paied_amount')->nullable();
            $table->double('remaining_amount')->nullable();
            $table->enum('status',['pending','accepted','published','rejected','completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casees');
    }
};
