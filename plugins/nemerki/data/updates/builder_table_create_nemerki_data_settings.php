<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateNemerkiDataSettings extends Migration
{
    public function up()
    {
        Schema::create('nemerki_data_settings', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('lazt_user_id')->nullable()->unsigned();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('nemerki_data_settings');
    }
}
