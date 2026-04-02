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
        Schema::create('preset_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exercise_id')->constrained();
            $table->enum('card_type', ['number', 'jack', 'queen', 'king', 'ace', 'joker']);
            $table->enum('suit', ['hearts', 'diamonds', 'clubs', 'spades', 'red', 'black', 'any'])->default('any');
            $table->enum('unit', ['reps', 'meters', 'seconds']);
            $table->unsignedSmallInteger('value')->default(1);
            $table->timestamps();

            $table->unique(['preset_id', 'card_type', 'suit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preset_rules');
    }
};
