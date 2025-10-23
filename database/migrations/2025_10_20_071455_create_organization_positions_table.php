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
        Schema::create('organization_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('position_type'); // 'core' or 'head_of_curriculum'
            $table->string('position_name'); // 'lead', 'co-lead', 'bendahara', 'secretary', 'pic_event', 'public_relationship', 'media_creative', 'hr', 'head_curriculum', or curriculum name
            $table->integer('order')->default(0); // untuk urutan tampilan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_positions');
    }
};
