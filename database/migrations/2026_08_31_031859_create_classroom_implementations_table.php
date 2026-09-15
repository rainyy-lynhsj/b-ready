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
        Schema::create('classroom_implementations', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('teacher_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('workshop_id')
                ->constrained('workshops')
                ->cascadeOnDelete();

            $table->foreignId('classroom_package_id')
                ->constrained('classroom_packages')
                ->cascadeOnDelete();

            $table->date('implementation_date');

            $table->enum('status', [
                'planned',
                'in_progress',
                'completed'
            ])->default('planned');

            $table->unsignedInteger('students_participated')->default(0);

            $table->unsignedInteger('students_completed_assessment')->default(0);

            $table->unsignedInteger('students_passed')->default(0);

            $table->unsignedInteger('students_failed')->default(0);

            $table->text('teacher_reflection')->nullable();

            $table->text('remarks')->nullable();

            $table->string('supporting_record')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_implementations');
    }
};
