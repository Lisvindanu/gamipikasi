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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('category', ['commitment', 'collaboration', 'initiative', 'responsibility', 'violation']);
            $table->integer('value');
            $table->text('note')->nullable();
            $table->foreignId('given_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();

            $table->index('category');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
