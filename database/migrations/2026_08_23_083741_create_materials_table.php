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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();

            $table->foreignId('module_id')
                ->constained('modules')
                ->cascadeOnDelete();

            $table->string('title');

            $table->enum('type', [ 'pdf', 'presentation', 'video', 'image', 'external_resource' ]);

            $table->string('file_path')->nullable();

            $table->string('external_url')->nullable();

            $table->text('description')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
