<?php namespace Nemerki\Profile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class UserAddAppFields extends Migration
{
    public function up()
    {

        Schema::table('users', function ($table) {
            $table->integer('app_id')->unsigned()->nullable()->index();
            $table->string('lat', 100)->nullable();
            $table->string('lng', 100)->nullable();
            $table->text('notes')->nullable();
            $table->text('private_notes')->nullable();
            $table->text('subscription')->nullable();
            $table->decimal('discount_percentage', 10, 2)->nullable();
            $table->decimal('credit_available', 10, 2)->nullable();
            $table->integer('pricelist_id')->unsigned()->nullable()->index();
            $table->string('qr', 100)->nullable();


        });
    }

    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function ($table) {
                $table->dropColumn([
                    'app_id',
                    'lat',
                    'lng',
                    'notes',
                    'private_notes',
                    'subscription',
                    'discount_percentage',
                    'credit_available',
                    'pricelist_id',
                    'qr'
                ]);
            });
        }
    }
}