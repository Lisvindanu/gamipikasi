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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Author
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null'); // Optional: specific to department
            $table->string('title');
            $table->text('content');
            $table->string('slug')->unique();
            $table->enum('visibility', ['public', 'internal'])->default('internal'); // public or internal (core team only)
            $table->enum('category', ['announcement', 'report', 'event', 'regulation', 'minutes', 'general'])->default('general');
            $table->boolean('is_pinned')->default(false); // For important posts
            $table->timestamp('published_at')->nullable(); // Scheduled publishing
            $table->timestamps();

            $table->index(['visibility', 'published_at']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
