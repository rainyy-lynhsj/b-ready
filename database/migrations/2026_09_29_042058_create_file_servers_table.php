<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_servers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->string('file_type'); // pdf, mp4, jpg, png, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_servers');
    }
};