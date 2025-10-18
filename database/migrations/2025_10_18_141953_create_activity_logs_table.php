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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Who performed the action
            $table->foreignId('task_id')->nullable()->constrained()->cascadeOnDelete(); // Related task
            $table->string('action'); // task_created, task_updated, task_completed, comment_added, attachment_uploaded, etc.
            $table->string('description'); // Human-readable description
            $table->json('metadata')->nullable(); // Additional data (old_status, new_status, etc.)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Indexes for faster queries
            $table->index(['user_id', 'created_at']);
            $table->index(['task_id', 'created_at']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
