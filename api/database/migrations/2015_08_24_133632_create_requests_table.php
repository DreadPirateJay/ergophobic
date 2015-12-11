<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->string('type');
            $table->dateTime('start_date');
            $table->integer('start_hours');
            $table->boolean('multi_day');
            $table->dateTime('end_date')->nullable();
            $table->integer('end_hours');
            $table->string('status')->default('Pending');
            $table->text('comments')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('requests');
    }
}
