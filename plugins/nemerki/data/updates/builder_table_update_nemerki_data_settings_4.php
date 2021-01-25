<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataSettings4 extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->integer('last_user_id')->default(1)->change();
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->integer('last_user_id')->default(0)->change();
        });
    }
}
