<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePracticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practice', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_name');
            $table->integer('question_id');
            $table->boolean('is_right_answer');
            $table->dateTime('answered_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('practice');
    }
}
