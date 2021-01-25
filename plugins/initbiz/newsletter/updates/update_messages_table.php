<?php namespace InITbiz\Newsletter\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class UpdateMessagesTable extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_messages', function ($table) {
            $table->renameColumn('sendto', 'send_to');
            $table->string('email_template')->nullable();
        });
    }

    public function down()
    {
        if (Schema::hasColumn('initbiz_newsletter_messages', 'send_to')) {
            Schema::table('initbiz_newsletter_messages', function ($table) {
                $table->renameColumn('send_to', 'sendto');
            });
        }

        if (Schema::hasColumn('initbiz_newsletter_messages', 'email_template')) {
            Schema::table('initbiz_newsletter_messages', function ($table) {
                $table->dropColumn('email_template');
            });
        }
    }
}
