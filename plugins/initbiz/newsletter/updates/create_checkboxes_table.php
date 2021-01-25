<?php
namespace InITbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCheckboxesTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_checkboxes', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->boolean('required')->nullable();
            $table->string('name')->unique();
            $table->text('text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_checkboxes');
    }
}
