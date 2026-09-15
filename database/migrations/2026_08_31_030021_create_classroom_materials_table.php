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
        Schema::create('classroom_materials', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('classroom_package_id')
                ->constrained('classroom_packages')
                ->cascadeOnDelete();

            $table->string('title');

            $table->enum('material_type', [
                'student_manual',
                'teacher_guide',
                'classroom_activity',
                'worksheet',
                'student_assessment',
                'answer_key',
                'other'
            ]);

            $table->string('file_path');

            $table->string('original_filename')->nullable();

            $table->text('description')->nullable();

            $table->unsignedInteger('sequence')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classroom_materials');
    }
};
