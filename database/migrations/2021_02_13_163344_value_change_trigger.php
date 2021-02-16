<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ValueChangeTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //instead of comparing NEW.value and OLD.value, I choose to insert new row regardless the changes is identical
        //This is due to comparison between NEW.value and OLD.value is not case sensitive
        //Therefore, updating from 'abc' to 'Abc' for example will not trigger in this case
        //However, by default in laravel controller there is already checking if updated value is the same as value inside database. If it's same it wont trigger update in database
        DB::unprepared('
        CREATE TRIGGER after_value_update
        AFTER UPDATE
        ON secret_lab FOR EACH ROW
        BEGIN
              INSERT INTO secret_lab_change(`key`, `old_value`, `updated_value`, `original_created_at`, `created_at`, `updated_at`) VALUES (OLD.`key`, OLD.`value`, NEW.`value`, OLD.`updated_at`, CONVERT_TZ(NOW(), @@session.time_zone, "+00:00"), CONVERT_TZ(NOW(), @@session.time_zone, "+00:00"));
        END;
        ');
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `after_value_update`');
    }
}
