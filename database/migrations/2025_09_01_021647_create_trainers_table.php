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
        Schema::create('trainers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Add this
            
            $table->string('cnic');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('contact');
            $table->string('specialization'); // Add this
            $table->integer('experience_years'); // Add this
            $table->string('qualification'); // Add this
            $table->string('phone'); // Add this
            $table->string('address'); // Add this
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
