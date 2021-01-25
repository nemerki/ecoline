<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataSelectables extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_selectables', function($table)
        {
            $table->integer('type_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_selectables', function($table)
        {
            $table->dropColumn('type_id');
        });
    }
}
