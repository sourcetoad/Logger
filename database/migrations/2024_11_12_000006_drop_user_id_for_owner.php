<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table->string('owner_type')->nullable();
            $table->integer('owner_id', false, true)->nullable();

            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table->addColumn(
                config('activity-logger.user.foreign_key_type', 'bigInteger'),
                'user_id',
                [
                    'autoIncrement' => false,
                    'unsigned' => true,
                ]
            )
                ->nullable();

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