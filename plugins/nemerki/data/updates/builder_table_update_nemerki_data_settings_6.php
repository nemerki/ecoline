<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataSettings6 extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->string('copyright')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->dropColumn('copyright');
        });
    }
}
