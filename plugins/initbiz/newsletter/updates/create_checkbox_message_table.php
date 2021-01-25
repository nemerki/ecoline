<?php
namespace InITbiz\Newsletter\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCheckbox_MessageTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_checkbox_message', function ($table) {
            $table->engine = 'InnoDB';
            $table->integer('checkbox_id')->unsigned();
            $table->integer('message_id')->unsigned();
            $table->primary(['checkbox_id', 'message_id'], 'initbiz_checkbox_message_primary');
        });
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_checkbox_message');
    }
}
