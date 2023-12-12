<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricule');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('adresse');
            $table->string('country');
            $table->string('city');
            $table->string('phone_number');
            $table->enum('status',['Actif','Inactif','Delete']);
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('slug',255);
            $table->rememberToken();
            $table->timestamps();
            $table->unsignedBigInteger('roles_id');
            $table->foreign('roles_id')->references('id')->on('gsc_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
