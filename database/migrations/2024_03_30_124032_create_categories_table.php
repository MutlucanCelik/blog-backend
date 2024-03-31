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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->string('name')->unique();
            $table->string('image');
            $table->boolean('status')->default(1);// 1 - aktif /0 - pasif
            $table->boolean('show_home_page_status')->default(0); // 1 - aktif /0 - pasif
            $table->timestamps();

            $table->foreign('parent_id')->on('categories')->references('id')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
