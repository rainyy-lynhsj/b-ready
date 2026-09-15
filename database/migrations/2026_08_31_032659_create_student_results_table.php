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
        Schema::create('student_results', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('classroom_implementation_id')
                ->constrained('classroom_implementations')
                ->cascadeOnDelete();

            $table->string('student_identifier');

            $table->decimal('score', 6, 2);

            $table->decimal('percentage', 5, 2);

            $table->enum('result', [
                'passed',
                'failed'
            ]);

            $table->timestamps();

            $table->unique([
                'classroom_implementation_id',
                'student_identifier'
            ], 'implementation_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_reports');
    }
};
