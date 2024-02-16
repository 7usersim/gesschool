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
            $table->id();
            $table->decimal('amount', 10, 2);
            $table->decimal('paid', 10, 2);
            $table->decimal('left_to_pay', 10, 2);
            $table->date('payment_date');
            $table->enum('payment_method',['espece','cheque']);
            $table->string('payment_reference')->nullable();
            $table->enum('payment_status', ['Paid', 'Pending'])->default('Pending');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('gsc_students')->onDelete('cascade');
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
        Schema::dropIfExists('frais_de_scolarites');
    }
}
