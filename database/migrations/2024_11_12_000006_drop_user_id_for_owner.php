<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table->morphs('owner');
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table
                ->foreign('user_id')
                ->references(config('activity-logger.user.foreign_key', 'id'))
                ->on(config('activity-logger.user.table', 'users'))
                ->onDelete('RESTRICT');
            $table->dropColumn('owner_id');
            $table->dropColumn('owner_type');
        });
    }
};
