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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('commit_id')->constrained('commits')->onDelete('cascade');
            $table->foreignId('repo_id')->constrained('repos')->onDelete('cascade');
            $table->foreignId('previous_version_id')->constrained('files')->onDelete('set null');
            $table->integer('file_size');
            $table->string('file_path');
            $table->boolean('is_latest')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
