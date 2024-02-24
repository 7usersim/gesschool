<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->float('note');
            $table->unsignedBigInteger('id_evaluation');
            $table->foreign('id_evaluation')->references('id')->on('gsc_evaluation')->OnDelete('cascade');
            $table->unsignedBigInteger('id_courses');
            $table->foreign('id_courses')->references('id')->on('gsc_classes_courses')->OnDelete('cascade');
            $table->unsignedBigInteger('id_students');
            $table->foreign('id_students')->references('id')->on('gsc_students')->OnDelete('cascade');
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
        Schema::dropIfExists('gsc_notes');
    }
}
