<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataProducts extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_products', function($table)
        {
            $table->integer('app_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_products', function($table)
        {
            $table->dropColumn('app_id');
        });
    }
}
