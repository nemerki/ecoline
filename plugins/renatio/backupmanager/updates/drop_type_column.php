<?php

namespace Renatio\BackupManager\Updates;

use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;
use Renatio\BackupManager\Models\Settings;

class DropTypeColumn extends Migration
{

    public function up()
    {
        Schema::table('renatio_backupmanager_backups', function ($table) {
            $table->dropColumn('type');
        });

        $settings = Settings::instance();

        $settings->resetDefault();
    }

    public function down()
    {
        Schema::table('renatio_backupmanager_backups', function ($table) {
            $table->enum('type', ['db', 'app']);
        });
    }
}
