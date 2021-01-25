<?php namespace InITbiz\Newsletter\Updates;

use File;
use Schema;
use SplTempFileObject;
use League\Csv\Writer as CsvWriter;
use Initbiz\Newsletter\Models\Subscriber;
use October\Rain\Database\Updates\Migration;

class UpdateSubscribersTable extends Migration
{
    public function up()
    {
        $subscribers = Subscriber::all()->pluck('email', 'agreed');

        $csv = CsvWriter::createFromFileObject(new SplTempFileObject);
        $csv->setOutputBOM(CsvWriter::BOM_UTF8);

        $csv->insertAll($subscribers);

        $csvPath = temp_path().'/subscribers.bak';
        $output = $csv->__toString();

        File::put($csvPath, $output);

        Schema::table('initbiz_newsletter_subscribers', function ($table) {
            $table->dropColumn('agreed');
            $table->unique('email');
        });
    }

    public function down()
    {
        Schema::table('initbiz_newsletter_subscribers', function ($table) {
            $table->boolean('agreed', false)->nullable();

            $indexes = Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($table->getTable());
            $index_name = 'initbiz_newsletter_subscribers_email_unique';

            if (array_key_exists($index_name, $indexes)) {
                $table->dropUnique($index_name);
            }
        });
    }
}
