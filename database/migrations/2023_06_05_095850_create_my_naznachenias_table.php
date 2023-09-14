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
        Schema::create('my_naznachenias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('PATIENTS_ID')->nullable();
            $table->string('PATDIREC_ID')->nullable();
            $table->string('INTAKE_METHOD')->nullable();
            $table->string('PATDIREC_DRUGS_ID')->nullable();
            $table->string('MEDICAMENT')->nullable();
            $table->string('COUNT_PER_DAY')->nullable();
            $table->string('DOSE')->nullable();
            $table->string('time')->nullable();
            $table->string('CREATE_DATE_TIME')->nullable();
            $table->longText('DESCRIPTION')->nullable();
            $table->string('BEGIN_DATE_TIME')->nullable();
            $table->string('END_DATE_TIME')->nullable();
            $table->string('PLANE_DATE')->nullable();
            $table->string('status')->default(0);


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
        Schema::dropIfExists('my_naznachenias');
    }
};
