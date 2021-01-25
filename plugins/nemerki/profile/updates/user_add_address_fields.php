<?php namespace Nemerki\Profile\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class UserAddAddressFields extends Migration
{
    public function up()
    {

        Schema::table('users', function ($table) {

            $table->text('address')->nullable();



        });
    }

    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function ($table) {
                $table->dropColumn([
                    'address',
                ]);
            });
        }
    }
}