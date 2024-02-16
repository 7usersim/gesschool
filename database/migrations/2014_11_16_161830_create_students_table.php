<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_students', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->string('firstname')->unique();
            $table->string('lastname')->unique();
            $table->enum('sexe',['male','female']);
            $table->date('date_birth');
            $table->string('email')->unique();
            $table->string('parent_name');
            $table->string('address');
            $table->unsignedBigInteger('id_cycle');
            $table->foreign('id_cycle')->references('id')->on('gsc_cycle')->onDelete('cascade');
            $table->unsignedBigInteger('id_field');
            $table->foreign('id_field')->references('id')->on('gsc_filiere')->onDelete('cascade');
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
        Schema::dropIfExists('students');
    }
}
