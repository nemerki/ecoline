<?php namespace Initbiz\Newsletter\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateInitbizNewsletterCheckboxes extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('initbiz_newsletter_checkboxes', 'required')) {
            Schema::table('initbiz_newsletter_checkboxes', function ($table) {
                $table->boolean('required')->nullable(false)->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('initbiz_newsletter_checkboxes', 'required')) {
            Schema::table('initbiz_newsletter_checkboxes', function ($table) {
                $table->boolean('required')->nullable()->change();
            });
        }
    }
}
