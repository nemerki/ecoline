<?php namespace Nemerki\Migration\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateNemerkiMigrationMigration extends Migration
{
    public function up()
    {
        Schema::create('nemerki_migration_migration', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('nemerki_migration_migration');
    }
}
