<?php namespace Nemerki\Data\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateNemerkiDataProducts extends Migration
{
    public function up()
    {
        Schema::create('nemerki_data_products', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->integer('category_id')->nullable()->unsigned();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('price_express', 10, 2)->nullable();
            $table->integer('pieces')->nullable()->unsigned();
            $table->boolean('is_active')->default(1);
            $table->integer('sort_order')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('nemerki_data_products');
    }
}
