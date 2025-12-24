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
        // Safely modify ENUM without data loss
        DB::statement("ALTER TABLE `email_logs` MODIFY COLUMN `status` 
            ENUM('queued', 'sending', 'sent', 'failed', 'partial') 
            NOT NULL DEFAULT 'queued'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `email_logs` MODIFY COLUMN `status` 
            ENUM('queued', 'sending', 'sent', 'failed', 'partial') NOT NULL DEFAULT 'queued'");
    }
};
