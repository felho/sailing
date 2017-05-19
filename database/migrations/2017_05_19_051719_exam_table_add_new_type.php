<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExamTableAddNewType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exam', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('exam', function (Blueprint $table) {
            $table->enum('type', array('motorboat', 'sailboat', 'regulation'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exam', function (Blueprint $table) {
            $table->dropColumn('type');
        });
        Schema::table('exam', function (Blueprint $table) {
            $table->enum('type', array('motorboat', 'sailboat'));
        });
    }
}
