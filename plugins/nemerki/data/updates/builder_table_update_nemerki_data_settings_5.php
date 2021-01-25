<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataSettings5 extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->string('header_logo')->nullable();
            $table->string('footer_logo')->nullable();
            $table->text('social')->nullable();
            $table->string('header_open')->nullable();
            $table->string('header_phone')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_settings', function($table)
        {
            $table->dropColumn('header_logo');
            $table->dropColumn('footer_logo');
            $table->dropColumn('social');
            $table->dropColumn('header_open');
            $table->dropColumn('header_phone');
        });
    }
}
