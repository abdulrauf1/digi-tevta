<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
       Schema::create('daily_lesson_plan_enrollment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('daily_lesson_plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->enum('attendance_status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Custom shorter index name
            $table->unique(['daily_lesson_plan_id', 'enrollment_id'], 'dlp_enrollment_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('daily_lesson_plan_enrollment');
    }
};
