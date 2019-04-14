<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeNullableFieldsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('histories', function($table)
        {
            $table->dateTime('time_at_doctor')->nullable()->change();
            $table->text('diagnosis')->nullable()->change();
            $table->text('analysis')->nullable()->change();
            $table->text('treatment')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
