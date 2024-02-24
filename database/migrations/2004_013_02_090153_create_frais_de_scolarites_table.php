<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFraisDeScolaritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gsc_frais', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('paid', 10, 2);
            $table->decimal('left_to_pay', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method',['espece','cheque','om','mobile','eu']);
            $table->string('payment_reference')->nullable();
            $table->enum('payment_status', ['Paid', 'Pending'])->default('Pending');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('gsc_students')->onDelete('cascade');
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('gsc_classes')->onDelete('cascade');
            $table->json('historique')->nullable();
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
        Schema::dropIfExists('gsc_frais');
    }
}
