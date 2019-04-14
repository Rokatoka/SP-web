<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeNullableDoctorsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors', function($table)
        {
            $table->integer('user_id')->unsigned()->nullable()->change();
            $table->integer('position_id')->unsigned()->nullable()->change();
            $table->integer('department_id')->unsigned()->nullable()->change();
            $table->integer('schedule_id')->unsigned()->nullable()->change();
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
