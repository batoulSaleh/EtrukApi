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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casee_id')->constrained('casees');
            $table->enum('name_en',['chair', 'bed', 'table', 'sofa', 'refrigerator', 'cooker', 'washing machine', 'fan']);
            $table->enum('name_ar',['كرسي ','سرير ','منضدة','اريكة','ثلاجة','بوتجاز','غسالة','مروحة']);
            $table->double('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
