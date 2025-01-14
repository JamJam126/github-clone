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
        Schema::create('commits', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('repo_id')->constrained('repos')->onDelete('cascade');
            $table->timestamps();

            // $table->index(['user_id', 'repo_id']);
            // Nvm this hurts my brain ^-('_')-^
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commits');
    }
};
