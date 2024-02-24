<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('roles_id');
            $table->foreign('roles_id')->references('id')->on('gsc_roles')->onDelete('cascade');
            $table->unsignedBigInteger('permissions_roles_id');
            $table->foreign('permissions_roles_id')->references('id')->on('permissions_roles')->onDelete('cascade');
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
        Schema::dropIfExists('permissions_users');
    }
}
