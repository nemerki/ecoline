<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateNemerkiDataBlogs extends Migration
{
    public function up()
    {
        Schema::table('nemerki_data_blogs', function($table)
        {
            $table->boolean('is_home')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('nemerki_data_blogs', function($table)
        {
            $table->dropColumn('is_home');
        });
    }
}
