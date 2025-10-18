<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update role enum to include bendahara
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('lead', 'co-lead', 'bendahara', 'secretary', 'head', 'member') NOT NULL DEFAULT 'member'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove bendahara from enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('lead', 'co-lead', 'secretary', 'head', 'member') NOT NULL DEFAULT 'member'");
    }
};
