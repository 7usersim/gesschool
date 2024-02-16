<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtablissementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_etablissement', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique;
            $table->string('adresse');
            $table->string('code_postal')->nullable()->unique;
            $table->string('ville');
            $table->string('pays');
            $table->string('fax')->nullable()->unique;
            $table->string('telephone')->unique;
            $table->string('email')->nullable()->unique;;
            $table->string('site_web')->nullable()->unique;;
            $table->date('date_fondation')->nullable();
            $table->enum('type_ets',['general','technique']);
            $table->string('logo')->nullable()->unique;
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
        Schema::dropIfExists('etablissements');
    }
}
