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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('title');                  // Pamagat ng Assessment
            $table->integer('passing_score');          // Kailangang score para pumasa
            $table->integer('time_limit')->nullable(); // Oras limit sa minutes (optional)
            $table->integer('number_of_attempts')->default(1); // Ilang beses pwede ulitin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};