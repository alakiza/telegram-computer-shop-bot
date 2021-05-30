<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('surname');
            $table->text('patronymic');
            $table->integer('card_number');
            $table->integer('chamber');
            $table->date('receipt_date');
            $table->integer('id_doctors');

            $table->foreign('id_doctors')->references('id')->on('doctors');
            $table->foreign('chamber')->references('id')->on('chambers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
