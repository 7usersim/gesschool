<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilieresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_filiere', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('cycle_id');
            $table->foreign('cycle_id')->references('id')->on('gsc_cycle')->onDelete('cascade');
            $table->unsignedBigInteger('school_id');
            $table->foreign('user_id')->references('id')->on('gsc_etablissement')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('gsc_users')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gsc_filiere');
    }
}
