<?php
// database/migrations/2025_12_24_0200_emergency_campaign_status_recovery.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Check if status column exists
        if (!Schema::hasColumn('campaigns', 'status')) {
            Schema::table('campaigns', function (Blueprint $table) {
                $table->enum('status', ['draft', 'queued', 'sending', 'sent', 'failed'])
                      ->default('draft')
                      ->nullable(false)
                      ->after('name'); // Put after name column
            });
        }

        // 2. Fix NULL or invalid statuses
        $invalidStatuses = DB::table('campaigns')
            ->whereNotIn('status', ['draft', 'queued', 'sending', 'sent', 'failed'])
            ->orWhereNull('status')
            ->pluck('id');

        if ($invalidStatuses->isNotEmpty()) {
            DB::table('campaigns')
                ->whereIn('id', $invalidStatuses)
                ->update(['status' => 'draft']);
        }

        // 3. Log success
        \Log::info('Campaign status column recovered successfully');
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
