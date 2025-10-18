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
        Schema::table('badges', function (Blueprint $table) {
            $table->string('criteria_type')->nullable()->after('icon'); // 'points', 'assessments', 'manual'
            $table->integer('criteria_value')->nullable()->after('criteria_type'); // threshold value
            $table->boolean('auto_award')->default(false)->after('criteria_value'); // auto award or manual
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('badges', function (Blueprint $table) {
            $table->dropColumn(['criteria_type', 'criteria_value', 'auto_award']);
        });
    }
};
