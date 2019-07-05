<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Sourcetoad\Logger\Models\AuditChange;
use Sourcetoad\Logger\Models\AuditKey;

class UpdateLoggerTables extends Migration
{
    public function up()
    {
        if (config('database.default') !== 'mysql') {
            throw new \InvalidArgumentException("MySQL is the only supported driver for this package.");
        }

        Schema::table('audit_changes', function (Blueprint $table) {
            $table->boolean('processed')->default(false);
            $table->bigInteger('key_id', false, true)->after('user_id')->nullable(true);

            $table
                ->foreign('key_id')
                ->references('id')
                ->on('audit_keys')
                ->onDelete('RESTRICT');
        });

        Schema::table('audit_models', function (Blueprint $table) {
            $table->boolean('processed')->default(false);
        });

        $dispatcher = AuditChange::getEventDispatcher();
        AuditChange::unsetEventDispatcher();

        AuditChange::query()->chunkById(200, function ($changes) {
            /** @var AuditChange $change */
            foreach ($changes as $change) {
                $fields = array_flip(json_decode($change->fields, true));
                $keys = AuditKey::createOrFind($fields);
                $change->key_id = $keys->id;
                $change->saveOrFail();
            }
        });

        AuditChange::setEventDispatcher($dispatcher);
    }

    public function down()
    {
        Schema::table('audit_changes', function (Blueprint $table) {
            $table->dropForeign(['key_id']);
            $table->dropColumn('key_id');
            $table->dropColumn('processed');
        });

        Schema::table('audit_models', function (Blueprint $table) {
            $table->dropColumn('processed');
        });
    }
}