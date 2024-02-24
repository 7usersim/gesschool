<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_time_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('date');
            $table->string('starting_hour');
            $table->string('closing_hour');
            $table->unsignedBigInteger('id_course');
            $table->foreign('id_course')->references('course_id')->on('gsc_classes_courses')->onDelete('cascade');
            $table->unsignedBigInteger('id_classe');
            $table->foreign('id_classe')->references('id')->on('gsc_classes')->onDelete('cascade');
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
        Schema::dropIfExists('gsc_time_tables');
    }
}
