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
         Schema::create('trainee_assessment_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->decimal('attendance_percentage', 5, 2);    
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['formative', 'integrated']);
            $table->enum('status', ['pending', 'completed', 'incomplete'])->default('pending');
            $table->date('submission_date')->nullable();
            $table->string('evidence_guide_link');   
            $table->enum('result', ['competent', 'not yet competent', 'incomplete'])->default('incomplete');         
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_assessment_files');
    }
};
