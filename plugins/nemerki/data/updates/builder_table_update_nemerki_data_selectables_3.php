<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataSelectables3 extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_selectables', function($table)
        {
            $table->string('img')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_selectables', function($table)
        {
            $table->dropColumn('img');
        });
    }
}
