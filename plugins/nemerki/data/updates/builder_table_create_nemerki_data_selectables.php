<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateNemerkiDataSelectables extends Migration
{
    public function up()
    {
        Schema::create('nemerki_data_selectables', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->smallInteger('sort_order')->nullable()->unsigned();
            $table->boolean('is_active')->default(1);
            $table->integer('app_id')->nullable()->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('nemerki_data_selectables');
    }
}
