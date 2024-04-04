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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('body');
            $table->string('image')->nullable();
            $table->boolean('status')->default(1); // 1 - akttif / 0 - pasif
            $table->integer('reading_time'); //kelime sayısına göre hesaplanacak
            $table->dateTime('publish_date')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->on('categories')->references('id')->onDelete('cascade');
            $table->foreign('user_id')->on('users')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
