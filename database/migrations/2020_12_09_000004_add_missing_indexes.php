<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingIndexes extends Migration
{
    public function up(): void
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table->index('processed');
        });

        Schema::table('audit_models', function (Blueprint $table) {
            $table->index('processed');
        });
    }

    public function down(): void
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table->dropIndex(['processed']);
        });

        Schema::table('audit_models', function (Blueprint $table) {
            $table->dropIndex(['processed']);
        });
    }
}
