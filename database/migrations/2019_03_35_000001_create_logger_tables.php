<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLoggerTables extends Migration
{
    public function up(): void
    {
        Schema::create('audit_routes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('route');
            $table->string('route_hash', 32)->unique();
        });

        Schema::create('audit_keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->json('data');
            $table->string('hash', 32)->unique();
        });

        Schema::create('audit_activities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('key_id', false, true)->nullable(true);
            $table->integer('route_id', false, true);
            $table->addColumn(
                config('activity-logger.user.foreign_key_type', 'bigInteger'),
                'user_id',
                [
                    'autoIncrement' => false,
                    'unsigned' => true
                ]
            )
                ->nullable();

            $table->tinyInteger('type', false, true);
            $table->tinyInteger('verb', false, true);
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references(config('activity-logger.user.foreign_key', 'id'))
                ->on(config('activity-logger.user.table', 'users'))
                ->onDelete('RESTRICT');

            $table
                ->foreign('route_id')
                ->references('id')
                ->on('audit_routes')
                ->onDelete('RESTRICT');

            $table
                ->foreign('key_id')
                ->references('id')
                ->on('audit_keys')
                ->onDelete('RESTRICT');
        });

        if (DB::getDriverName() === 'pgsql') {
            // PostgreSQL-specific SQL
            DB::statement('ALTER TABLE audit_activities ADD ip_address BYTEA');
        } else {
            // MySQL-specific SQL
            DB::statement('ALTER TABLE `audit_activities` ADD `ip_address` VARBINARY(16) AFTER `type`');
        }

        Schema::create('audit_models', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_id', false, true);
            $table->mediumInteger('entity_type', false, true);
            $table->integer('entity_id', false, true);
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
                ->foreign('activity_id')
                ->references('id')
                ->on('audit_activities')
                ->onDelete('RESTRICT');

            $table
                ->foreign('user_id')
                ->references(config('activity-logger.user.foreign_key', 'id'))
                ->on(config('activity-logger.user.table', 'users'))
                ->onDelete('RESTRICT');
        });

        Schema::create('audit_changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('activity_id', false, true);
            $table->mediumInteger('entity_type', false, true);
            $table->integer('entity_id', false, true);
            $table->addColumn(
                config('activity-logger.user.foreign_key_type', 'bigInteger'),
                'user_id',
                [
                    'autoIncrement' => false,
                    'unsigned' => true,
                ]
            )
                ->nullable();

            $table->json('fields');

            $table
                ->foreign('activity_id')
                ->references('id')
                ->on('audit_activities')
                ->onDelete('RESTRICT');

            $table
                ->foreign('user_id')
                ->references(config('activity-logger.user.foreign_key', 'id'))
                ->on(config('activity-logger.user.table', 'users'))
                ->onDelete('RESTRICT');
        });

        Schema::table('audit_activities', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::drop('audit_changes');
        Schema::drop('audit_routes');
        Schema::drop('audit_keys');
        Schema::drop('audit_models');
        Schema::drop('audit_activities');
        Schema::enableForeignKeyConstraints();
    }
}
