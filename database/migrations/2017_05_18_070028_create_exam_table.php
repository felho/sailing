<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', array('motorboat', 'sailboat'));
            $table->string('question');
            $table->string('good_answer');
            $table->string('bad_answer1');
            $table->string('bad_answer2');
            $table->binary('picture');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exam');
    }
}
