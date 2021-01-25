<?php namespace Initbiz\Newsletter\Updates;

use Schema;
use Initbiz\Newsletter\Models\Checkbox;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateInitbizNewsletterCheckboxes2 extends Migration
{
    public function up()
    {
        Schema::table('initbiz_newsletter_checkboxes', function ($table) {
            $table->string('slug')->unique()->nullable();
        });
    }

    public function down()
    {
        Schema::table('initbiz_newsletter_checkboxes', function ($table) {
            $table->dropColumn('slug');
        });
    }
}
