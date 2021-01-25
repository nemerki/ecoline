<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataSettings2 extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->dropColumn('last_user_id');
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->integer('last_user_id')->nullable()->unsigned();
        });
    }
}
