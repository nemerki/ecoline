<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataSettings extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->renameColumn('lazt_user_id', 'last_user_id');
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->renameColumn('last_user_id', 'lazt_user_id');
        });
    }
}
