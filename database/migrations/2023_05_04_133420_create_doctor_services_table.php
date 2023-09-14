<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('doctor_lists') ->onDelete('cascade');

            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('doctor_subjects') ->onDelete('cascade');

            $table->string('PL_EXAM_ID')->nullable();
            $table->string('name_exam')->nullable();
            $table->string('specialisation_name')->nullable();
            $table->string('EXAM_ORDER')->nullable();
            $table->string('DUREE')->nullable();
            $table->string('priceAmount')->nullable();
            $table->string('FM_INTORG_ID')->nullable();
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
        Schema::dropIfExists('doctor_services');
    }
};
