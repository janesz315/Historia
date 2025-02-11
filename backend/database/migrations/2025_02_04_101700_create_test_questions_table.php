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
        Schema::create('test_questions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('questionId');
            $table->integer('answerId');
            $table->integer('userTestId');
            $table->foreign('questionId')->references('id')->on('questions');
            $table->foreign('answerId')->references('id')->on('answers');
            $table->foreign('userTestId')->references('id')->on('user_tests')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_questions');
    }
};
