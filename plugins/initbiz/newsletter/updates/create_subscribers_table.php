<?php namespace InITbiz\Newsletter\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class CreateSubscribersTable extends Migration
{
    public function up()
    {
        Schema::create('initbiz_newsletter_subscribers', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email', 100);
            $table->boolean('confirmed', false);
            $table->string('token', 40);
            $table->boolean('agreed', false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('initbiz_newsletter_subscribers');
    }
}
