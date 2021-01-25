<?php

namespace Renatio\BackupManager\Updates;

use October\Rain\Database\Updates\Migration;
use October\Rain\Support\Facades\Schema;

class CreateBackupsTable extends Migration
{

    public function up()
    {
        Schema::create('renatio_backupmanager_backups', function ($table) {
            $table->increments('id');
            $table->string('disk_name');
            $table->string('file_path');
            $table->enum('type', ['db', 'app']);
            $table->string('filesystems');
            $table->unsignedInteger('file_size');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('renatio_backupmanager_backups');
    }
}
