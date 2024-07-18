<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnusedLoggerColumns extends Migration
{
    public function up(): void
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table->dropColumn('fields');
        });
    }

    public function down(): void
    {
        // Reversing this migration is not supported due to 1 way changes.
    }
}
