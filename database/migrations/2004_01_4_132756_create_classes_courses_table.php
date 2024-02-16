<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassesCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_classes_courses', function (Blueprint $table) {
            $table->id();
            $table->integer('credit');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('class_id');
            $table->foreign('course_id')->references('id')->on('gsc_courses')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('gsc_classes')->onDelete('cascade');
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
        Schema::dropIfExists('classes_courses');
    }
}
