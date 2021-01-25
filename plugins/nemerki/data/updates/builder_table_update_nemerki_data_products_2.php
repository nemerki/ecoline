<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataProducts2 extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_products', function($table)
        {
            $table->string('img')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_products', function($table)
        {
            $table->dropColumn('img');
        });
    }
}
