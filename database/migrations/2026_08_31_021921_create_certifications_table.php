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
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('workshop_id')
                ->constrained('workshops')
                ->cascadeOnDelete();

            $table->foreignId('assessment_attempt_id')
                ->constrained('assessment_attempts')
                ->cascadeOnDelete();

            $table->string('certificate_number')->unique();

            $table->string('badge_name');

            $table->string('certificate_file')->nullable();

            $table->timestamp('certified_at');

            $table->timestamps();

            $table->unique([
                'teacher_id',
                'workshop_id'
            ], 'teacher_workshop_certification_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
