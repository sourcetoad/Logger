<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddMissingIndexes extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            throw new \InvalidArgumentException("MySQL is the only supported driver for this package.");
        }

        Schema::table('audit_changes', function (Blueprint $table) {
            $table->index('processed');
        });

        Schema::table('audit_models', function (Blueprint $table) {
            $table->index('processed');
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            throw new \InvalidArgumentException("MySQL is the only supported driver for this package.");
        }

        Schema::table('audit_changes', function (Blueprint $table) {
            $table->dropIndex(['processed']);
        });

        Schema::table('audit_models', function (Blueprint $table) {
            $table->dropIndex(['processed']);
        });
    }
}
