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
        Schema::create('daily_lesson_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->foreignId('trainer_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('topic');
            $table->text('objectives');
            $table->text('materials_needed')->nullable();
            $table->text('activities');
            $table->text('assessment_methods');
            $table->text('homework_assignment')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['planned', 'completed', 'postponed', 'cancelled'])->default('planned');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate lessons on same date for same module
            $table->unique(['module_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_lesson_plans');
    }
};
