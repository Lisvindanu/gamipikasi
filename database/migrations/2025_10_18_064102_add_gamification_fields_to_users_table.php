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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['lead', 'co-lead', 'secretary', 'head', 'member'])->default('member')->after('email')->index();
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null')->after('role');
            $table->integer('total_points')->default(0)->after('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'department_id', 'total_points']);
        });
    }
};
