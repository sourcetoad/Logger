<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropUnusedLoggerColumns extends Migration
{
    public function up()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            throw new \InvalidArgumentException("MySQL is the only supported driver for this package.");
        }

        Schema::table('audit_changes', function (Blueprint $table) {
            $table->dropColumn('fields');
        });
    }

    public function down()
    {
        throw new BadMethodCallException('Reversing this migration is not supported.');
    }
}
